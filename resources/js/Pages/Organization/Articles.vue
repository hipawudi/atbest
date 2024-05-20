<template>
  <OrganizationLayout :title="$t('articles')">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $t("articles") }}
      </h2>
    </template>
    <div class="flex justify-end pb-3 gap-3">
      <template v-if="isDrop">
        <a-button type="primary" @click="isDrop = !isDrop">{{ $t("cancel") }}</a-button>
        <a-button type="text" @click="saveSequence">{{ $t("save") }}</a-button>
      </template>
      <template v-else>
        <a-button type="primary" @click="isDrop = !isDrop">{{
          $t("dragger_sort")
        }}</a-button>
        <inertia-link
          :href="route('manage.articles.create')"
          class="ant-btn ant-btn-primary"
          >{{ $t("create_article") }}</inertia-link
        >
      </template>
    </div>
    <div class="bg-white relative shadow rounded-lg">
      <div class="ant-table">
        <div class="ant-table-container">
          <table style="table-layout: auto">
            <thead class="ant-table-thead">
              <tr>
                <th v-for="column in columns" :key="column.id">{{ $t(column.i18n) }}</th>
              </tr>
            </thead>
            <draggable
              tag="tbody"
              class="dragArea list-group ant-table-tbody"
              :list="articles.data"
              :disabled="!isDrop"
              @change="onDragEnd"
            >
              <transition-group v-for="(record, idx) in articles.data" :key="idx">
                <tr class="ant-table-row ant-table-row-level-0" :key="record.id">
                  <td
                    v-for="column in columns"
                    class="ant-table-cell"
                    :class="isDrop ? 'cursor-grab' : ''"
                    :key="column.id"
                  >
                    <template v-if="column.dataIndex == 'operation'">
                      <a-button @click="editRecord(record)">{{ $t("edit") }}</a-button>
                    </template>
                    <template v-else-if="column.dataIndex == 'dragger'">
                      <div class="flex items-center">
                        <template v-if="isDrop == true"><holder-outlined /></template>
                        {{ record.sequence }}
                      </div>
                    </template>
                    <template v-else-if="column.dataIndex == 'published'">
                      {{ record.published ? $t("yes") : $t("no") }}
                    </template>
                    <template v-else>
                      {{ record[column.dataIndex] }}
                    </template>
                  </td>
                </tr>
              </transition-group>
            </draggable>
          </table>
          <div class="text-right p-3 px-6">
            <a-pagination
              v-model:current="articles.current_page"
              :total="articles.total"
              v-model:page-size="articles.per_page"
            />
          </div>
        </div>
      </div>
    </div>
    <p>Article CAN NOT be delete if published.</p>

    <!-- Modal Start-->
    <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="100%">
      <a-form
        ref="modalRef"
        :model="modal.data"
        name="Teacher"
        layout="vertical"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
      >
        <a-form-item :label="$t('article_category')" name="category_code">
          <a-select
            v-model:value="modal.data.category_code"
            :options="articleCategories"
          />
        </a-form-item>
        <a-form-item :label="$t('title')" name="title">
          <a-input v-model:value="modal.data.title" />
        </a-form-item>
        <a-form-item :label="$t('title')" name="title_fn">
          <a-input v-model:value="modal.data.title" />
        </a-form-item>
        <a-form-item :label="$t('content')" name="content_en">
          <ckeditor
            :editor="editor"
            v-model="modal.data.content_en"
            :config="editorConfig"
          />
        </a-form-item>
        <a-form-item :label="$t('valid_at')" name="valid_at">
          <a-date-picker
            v-model:value="modal.data.valid_at"
            :format="dateFormat"
            :valueFormat="dateFormat"
          />
        </a-form-item>
        <a-form-item :label="$t('expired_at')" name="expired_at">
          <a-date-picker v-model:value="modal.data.expire_at" :valueFormat="dateFormat" />
        </a-form-item>
        <a-form-item :label="$t('url')" name="url">
          <a-input v-model:value="modal.data.url" />
        </a-form-item>
        <a-row>
          <a-col>
            <a-form-item :label="$t('published')" name="published">
              <a-switch
                v-model:checked="modal.data.published"
                :checkedValue="1"
                :unCheckedValue="0"
                @change="modal.data.public = 0"
              />
            </a-form-item>
          </a-col>
          <a-col class="pl-10" v-if="modal.data.published">
            <a-form-item :label="$t('public')" name="public">
              <a-switch
                v-model:checked="modal.data.public"
                :checkedValue="1"
                :unCheckedValue="0"
              />
            </a-form-item>
          </a-col>
        </a-row>
      </a-form>
      <template #footer>
        <a-button
          v-if="modal.mode == 'EDIT'"
          key="Update"
          type="primary"
          @click="updateRecord()"
          >{{ $t("update") }}</a-button
        >
        <a-button
          v-if="modal.mode == 'CREATE'"
          key="Store"
          type="primary"
          @click="storeRecord()"
          >{{ $t("add") }}</a-button
        >
      </template>
    </a-modal>
    <!-- Modal End-->
  </OrganizationLayout>
</template>

<script>
import OrganizationLayout from "@/Layouts/OrganizationLayout.vue";
import { defineComponent, reactive } from "vue";
//import Editor from 'ckeditor5-custom-build/build/ckeditor';
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import UploadAdapter from "@/Components/ImageUploadAdapter.vue";
import { VueDraggableNext } from "vue-draggable-next";
import { HolderOutlined } from "@ant-design/icons-vue";

export default {
  components: {
    OrganizationLayout,
    ckeditor: CKEditor.component,
    UploadAdapter,
    draggable: VueDraggableNext,
    HolderOutlined,
    //UploadAdapter
  },
  props: ["classifies", "articleCategories", "articles"],
  data() {
    return {
      dateFormat: "YYYY-MM-DD",
      isDrop: false,
      modal: {
        isOpen: false,
        data: { content_en: "" },
        title: "Modal",
        mode: "",
      },
      pagination: {
        total: this.articles.total,
        current: this.articles.current_page,
        pageSize: this.articles.per_page,
      },
      editor: ClassicEditor,
      editorData: "<p>Content of the editor.</p>",
      editorConfig: {
        extraPlugins: [
          function (editor) {
            editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
              return new UploadAdapter(loader);
            };
          },
        ],
        // The configuration of the editor.
      },
      columns: [
        {
          title: "Dragger",
          i18n: "dragger_sort",
          dataIndex: "dragger",
        },
        {
          title: "Category",
          i18n: "category",
          dataIndex: "category_code",
        },
        {
          title: "Title",
          i18n: "title",
          dataIndex: "title",
        },
        {
          title: "Validated at",
          i18n: "valid_at",
          dataIndex: "valid_at",
        },
        {
          title: "Expired at",
          i18n: "expired_at",
          dataIndex: "expired_at",
        },
        {
          title: "Published",
          i18n: "published",
          dataIndex: "published",
        },
        {
          title: "Operation",
          i18n: "operation",
          dataIndex: "operation",
          key: "operation",
        },
      ],
      rules: {
        category_code: { required: true },
        classify_id: { required: true },
        title_en: { required: true },
      },
      validateMessages: {
        required: "${label} is required!",
        types: {
          email: "${label} is not a valid email!",
          number: "${label} is not a valid number!",
        },
        number: {
          range: "${label} must be between ${min} and ${max}",
        },
      },
      labelCol: {
        style: {
          width: "150px",
        },
      },
    };
  },
  created() {},
  mounted() {},
  methods: {
    onPaginationChange(page, filters, sorter) {
      this.$inertia.get(route("manage.articles.index"), {
        page: page.current,
        per_page: page.pageSize,
      });
    },
    onDragEnd(event) {
      //未知點做
      this.articles.data.forEach((element, idx) => {
        element.sequence = idx + 1;
      });
    },
    createRecord() {
      this.modal.data = {};
      this.modal.data.public = 0;
      this.modal.mode = "CREATE";
      this.modal.title = "Create";
      this.modal.isOpen = true;
    },
    editRecord(record) {
      this.modal.data = { ...record };
      this.modal.mode = "EDIT";
      //this.modal.title = "Edit";
      this.modal.isOpen = true;
    },
    storeRecord() {
      this.$refs.modalRef
        .validateFields()
        .then(() => {
          this.$inertia.post(route("manage.articles.store"), this.modal.data, {
            onSuccess: (page) => {
              this.modal.data = {};
              this.modal.isOpen = false;
            },
            onError: (err) => {
              console.log(err);
            },
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    updateRecord() {
      console.log(this.modal.data);
      this.$refs.modalRef
        .validateFields()
        .then(() => {
          this.$inertia.put(
            route("manage.articles.update", this.modal.data.id),
            this.modal.data,
            {
              onSuccess: (page) => {
                this.modal.data = {};
                this.modal.isOpen = false;
                console.log(page);
              },
              onError: (error) => {
                console.log(error);
              },
            }
          );
        })
        .catch((err) => {
          console.log("error", err);
        });
    },
    deleteConfirmed(record) {
      this.$inertia.delete(route("manage.articles.destroy", record.id), {
        onSuccess: (page) => {
          console.log(page);
        },
        onError: (error) => {
          console.log(error);
        },
      });
    },
    //deleteRecord(recordId) {
    //if (!confirm('Are you sure want to remove?')) return;
    // this.$inertia.delete(route('manage.articles.destroy', recordId), {
    //     onSuccess: (page) => {
    //         console.log(page);
    //     },
    //     onError: (error) => {
    //         console.log(error);
    //     }
    // });
    //},
    saveSequence() {
      this.$inertia.post(route("manage.article.sequence"), this.articles.data, {
        onSuccess: (page) => {
          console.log(page);
          this.isDrop = false;
        },
        onError: (error) => {
          console.log(error);
        },
      });
    },
    createLogin(recordId) {
      console.log("create login" + recordId);
    },
    uploader(editor) {
      editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
      };
    },
  },
};
</script>
