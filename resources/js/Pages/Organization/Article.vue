<template>
  <OrganizationLayout :title="$t('article')">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $t("article") }}
      </h2>
    </template>
    <!-- <a-button @click="getCursor()">{{ $t("cursor") }}</a-button> -->
    
      <div class="flex-auto pb-3 text-right pb-3">
        <a-button @click="isDrawerVisible = !isDrawerVisible" type="primary">{{
          $t("Images")
        }}</a-button>
      </div>
      <div class="bg-white relative shadow rounded-lg overflow-x-auto p-5">
        <a-form
          :model="article"
          name="Teacher"
          layout="vertical"
          autocomplete="off"
          :rules="rules"
          :validate-messages="validateMessages"
          @finish="onFinish"
          @finishFailed="onFinishFailed"
          enctype="multipart/form-data"
        >
          <a-form-item :label="$t('article_category')" name="category_code">
            <a-select
              v-model:value="article.category_code"
              :options="articleCategories"
            />
          </a-form-item>
          <a-form-item :label="$t('title')" name="title">
            <a-input v-model:value="article.title" />
          </a-form-item>
          <a-form-item :label="$t('intro')" name="intro">
            <a-textarea v-model:value="article.intro" :rows="5"/>
          </a-form-item>

          <a-form-item :label="$t('content')" name="content">
            <ckeditor
              ref="editorRef"
              :editor="editor"
              v-model="article.content"
              :config="editorConfig"
              :height="300"
            />
          </a-form-item>
          <a-form-item :label="$t('valid_at')" name="valid_at">
            <a-date-picker
              v-model:value="article.valid_at"
              :format="dateFormat"
              :valueFormat="dateFormat"
            />
          </a-form-item>
          <a-form-item :label="$t('expired_at')" name="expired_at">
            <a-date-picker v-model:value="article.expire_at" :valueFormat="dateFormat" />
          </a-form-item>
          <a-form-item :label="$t('url')" name="url">
            <a-input v-model:value="article.url" />
          </a-form-item>
          <a-row>
            <a-col>
              <a-form-item :label="$t('published')" name="published">
                <a-switch
                  v-model:checked="article.published"
                  @change="article.public = 0"
                />
              </a-form-item>
            </a-col>
            <a-col class="pl-10" v-if="article.published">
              <a-form-item :label="$t('public')" name="public">
                <a-switch
                  v-model:checked="article.public"
                />
              </a-form-item>
            </a-col>
          </a-row>
          <a-form-item :label="$t('tag')">
              <a-select v-model:value="article.tags" mode="tags" style="width: 100%" placeholder="Tags Mode"
                :options="tagOptions"></a-select>
            </a-form-item>

            <a-form-item :label="$t('thumbnail')">
              <template v-if="article.thumbnail">
                <img :src="article.thumbnail" width="300px" />
                <a @click="onDeleteImage(article)">Delete</a>
              </template>
              <template v-else>
                <template v-if="previewImage">
                  <img :src="previewImage" class="w-64"/>
                  <a @click="onRemoveImage">Remove</a>
                </template>
                <template v-else>
                  <div class="flex items-center justify-center w-64">
                    <label for="dropzone-file"
                      class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                          <div v-html="$t('upload_drag_drop')"/>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $t('image_size_note') }}</p>
                      </div>
                      <input id="dropzone-file" type="file" @change="onSelectFile" accept="image/png, image/gif, image/jpeg" style="display:none" />
                    </label>
                  </div>
                </template>
              </template>
            </a-form-item>

          <div class="flex flex-row item-center justify-center">
            <a-button type="primary" html-type="submit">{{ $t("submit") }}</a-button>
          </div>
        </a-form>
      </div>
      <a :href="route('article.item',{t:article.uuid})" target="_blank" ref="articleUrl">{{ route('article.item',{t:article.uuid}) }}</a>
      <a-button @click="copyUrl">{{ $t('copy_to_clipboard') }}</a-button>
      <p>Article CAN NOT be delete if published.</p>
    

    <a-drawer
      v-model:visible="isDrawerVisible"
      class="custom-class"
      title="Basic Drawer"
      placement="right"
      @after-visible-change="afterVisibleChange"
    >
      <div class="h-max-48 overflow-auto">
        Design for files and images 
        with media library<br>
        comming soon...
        <ul>
          <li v-for="media in medias" @click="selectMedia(media)">
            {{ media.file_name }}
          </li>
        </ul>
      </div>
      <div>
        <div v-if="selectedMedia">
          <img
            v-if="selectedMedia.preview_url"
            :src="selectedMedia.preview_url"
            width="100"
          />
          <img v-else :src="selectedMedia.original_url" width="100" />
          <a-button @click="addToArticle">{{ $t("add") }}</a-button>
        </div>
      </div>
    </a-drawer>
  </OrganizationLayout>
</template>

<script>
import OrganizationLayout from "@/Layouts/OrganizationLayout.vue";
import { defineComponent, reactive } from "vue";
//import Editor from 'ckeditor5-custom-build/build/ckeditor';
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import UploadAdapter from "@/Components/ImageUploadAdapter.vue";
import { message } from "ant-design-vue";

export default {
  components: {
    OrganizationLayout,
    ckeditor: CKEditor.component,
    UploadAdapter,
    //UploadAdapter
  },
  props: ["classifies", "articleCategories", "article"],
  data() {
    return {
      medias: [],
      previewImage: null,
      selectedMedia: null,
      isDrawerVisible: false,
      tagOptions: [{ 'value': '學習' }, { 'value': '公佈' }, { 'value': '交流' }, { 'value': '分享' }],
      dateFormat: "YYYY-MM-DD",
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
      rules: {
        category_code: { required: true },
        title: { required: true },
        valid_at: { required: true },
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
    onSelectFile(event) {
      const file =event.target.files[0]

      if(file.size > 1024*1024*1){
        alert('oversize')
        return false
      }
      //this.variffyUpload(file)
      this.article.thumbnail_upload = file
      this.previewImage = URL.createObjectURL(file)

    },
    onRemoveImage() {
      this.article.thumbial_upload = null
      this.previewImage = null
    },
    onDeleteImage(article) {
      this.$inertia.post(route('manage.article.deleteImage', this.article), {
        onSuccess: (page) => {
          console.log(page)
        },
        onError: (err) => {
          console.log(err);
        },
      })

    },
    uploader(editor) {
      editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
      };
    },
    onFinish(event) {
      if (this.article.id) {
        this.article._method='PATCH';
        this.$inertia.post(route("manage.articles.update", this.article.id), this.article, {
          onSuccess: (page) => {
            console.log(page);
          },
          onError: (error) => {
            console.log(error);
          },
        });
      } else {
        this.$inertia.post(route("manage.articles.store"), this.article, {
          onSuccess: (page) => {
            // this.modal.data = {};
            // this.modal.isOpen = false;
          },
          onError: (err) => {
            console.log(err);
          },
        });
      }
    },
    onFinishFailed({ values, errorFields, outOfDate }){
      message.error("Some required fields are missing!");
    },
    afterVisibleChange(bool) {
      if (bool) {
        axios.get(route("manage.medias", 22)).then((response) => {
          this.medias = response.data;
        });
      }
    },
    selectMedia(media) {
      this.selectedMedia = media;
    },
    addToArticle(meida) {
      var selection = this.editor.view.state.selection;
    },
    ckEditorFocusOut(event) {
    },
    copyUrl(){
      navigator.clipboard.writeText(this.$refs.articleUrl.href)
    }
  },
};
</script>

<style scope>
.ck-editor .ck-editor__main .ck-content {
  min-height: 500px;
}
</style>
