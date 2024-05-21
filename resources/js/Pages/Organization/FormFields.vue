<template>
  <OrganizationLayout title="表格欄位" :breadcrumb="breadcrumb">
    <div class="flex-auto pb-3 text-right">
      <button
        @click="createRecord()"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3"
      >
        {{ $t("create_field") }}
      </button>
    </div>
    <div class="bg-white relative shadow rounded-lg overflow-x-auto">
      <div class="ant-table">
        <div class="ant-table-container">
          <table style="table-layout: auto">
            <thead class="ant-table-thead">
              <tr>
                <th v-for="column in columns">{{ $t(column.i18n) }}</th>
              </tr>
            </thead>
            <draggable
              tag="tbody"
              class="dragArea list-group ant-table-tbody"
              :list="fields"
              @change="rowChange"
            >
              <transition-group v-for="(record, idx) in fields">
                <tr class="ant-table-row ant-table-row-level-0" :key="record.id">
                  <td v-for="column in columns" class="ant-table-cell">
                    {{ record[column.dataIndex] }}
                    <template v-if="column.dataIndex == 'operation'">
                      <a-button @click="editRecord(record)">{{ $t("edit") }}</a-button>
                      <a-button
                        @click="deleteRecord(record)"
                        :disabled="form.published == 1"
                        >{{ $t("delete") }}</a-button
                      >
                    </template>
                  </td>
                </tr>
              </transition-group>
            </draggable>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Start-->
    <a-modal
      v-model:visible="modal.isOpen"
      :title="modal.mode == 'CREATE' ? $t('create_field') : $t('edit_field')"
      width="60%"
    >
      <a-form
        ref="modalRef"
        :model="modal.data"
        name="formField"
        :label-col="{ span: 4 }"
        :wrapper-col="{ span: 20 }"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
        @finish="onFormFinish"
      >
        <!-- <a-form-item label="Field name" name="field_name">
                <a-input v-model:value="modal.data.field_name" />
            </a-form-item> -->
        <a-form-item :label="$t('field_label')" name="field_label">
          <a-input v-model:value="modal.data.field_label" @blur="onFieldLabelChanged" />
        </a-form-item>
        <a-form-item :label="$t('field_type')" name="type">
          <a-select
            v-model:value="modal.data.type"
            placeholder="Field Type"
            :options="fieldTypes"
            :fieldNames="{ value: 'value', label: 'label_zh' }"
            @change="onChangeType"
          />
        </a-form-item>
        <a-form-item
          :label="$t('row')"
          name="rows"
          v-if="['textarea', 'longtext', 'richtext'].includes(modal.data.type)"
        >
          <a-input-number v-model:value="modal.data.options" />
        </a-form-item>

        <template v-if="['radio', 'checkbox', 'dropdown'].includes(modal.data.type)">
          <a-form-item :label="$t('option')" name="options">
            <a-radio-group>
              <template v-for="option in modal.data.options">
                <a-radio :style="verticalStyle" :value="option.value">
                  <a-input v-model:value="option.label" />
                </a-radio>
              </template>
              <a-radio @click="addOptionItem"> Add option</a-radio>
            </a-radio-group>
            {{ modal.data.options }}
          </a-form-item>
          <a-form-item :label="$t('template')" name="optionTemplate">
            <a-select :options="templateOptions" @change="onChangeOptionTemplate" />
          </a-form-item>
          <a-form-item
            :label="$t('template')"
            name="optionTemplate"
            v-if="['radio', 'checkbox'].includes(modal.data.type)"
          >
            <a-radio-group v-model:value="modal.data.direction">
              <a-radio value="H">{{ $t("horizontal") }}</a-radio>
              <a-radio value="V">{{ $t("virtical") }}</a-radio>
            </a-radio-group>
          </a-form-item>
        </template>
        <a-form-item :label="$t('compulsory')" name="required">
          <a-switch
            v-model:checked="modal.data.required"
            :unCheckedValue="0"
            :checkedValue="1"
          />
        </a-form-item>
        <!-- <a-form-item label="規則" name="rule">
                <a-input v-model:value="modal.data.rule" />
            </a-form-item> -->
        <a-form-item
          :label="$t('column_data')"
          name="in_column"
          v-if="modal.data.required"
        >
          <a-switch
            v-model:checked="modal.data.in_column"
            :unCheckedValue="0"
            :checkedValue="1"
          />
        </a-form-item>
        <a-form-item :label="$t('remark')" name="remark">
          <a-textarea v-model:value="modal.data.remark" />
        </a-form-item>
      </a-form>
      <template #footer>
        <a-button @click="$refs.modalRef.$emit('finish')" type="primary">
          <span v-if="modal.mode == 'EDIT'">{{ $t("update") }}</span>
          <span v-if="modal.mode == 'CREATE'">{{ $t("create") }}</span>
        </a-button>
      </template>
    </a-modal>
    <!-- Modal End-->
  </OrganizationLayout>
</template>

<script>
import OrganizationLayout from "@/Layouts/OrganizationLayout.vue";
import { defineComponent, reactive } from "vue";
import { VueDraggableNext } from "vue-draggable-next";

export default {
  components: {
    OrganizationLayout,
    draggable: VueDraggableNext,
  },
  props: ["templateOptions", "form", "fields", "fieldTypes"],
  data() {
    return {
      breadcrumb: [
        { label: "表格列表", url: route("manage.forms.index") },
        { label: "表格欄位", url: null },
      ],
      //templateOptions:[],
      modal: {
        isOpen: false,
        data: {},
        title: "Modal",
        mode: "",
      },
      columns: [
        {
          title: "Field Label",
          i18n: "field_label",
          dataIndex: "field_label",
        },
        {
          title: "Field Type",
          i18n: "field_type",
          dataIndex: "type",
        },
        {
          title: "Compulsory",
          i18n: "compulsory",
          dataIndex: "required",
        },
        {
          title: "Column Data",
          i18n: "column_data",
          dataIndex: "in_column",
        },
        {
          title: "Operation",
          i18n: "operation",
          dataIndex: "operation",
        },
      ],
      rules: {
        field_label: { required: true },
        type: { required: true },
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
      verticalStyle: {
        display: "flex",
        height: "30px",
        lineHeight: "30px",
        width: "100%",
      },
    };
  },
  created() {},
  methods: {
    createRecord() {
      this.modal.data = {};
      this.modal.data.form_id = this.form.id;
      this.modal.data.direction = "V";
      this.modal.mode = "CREATE";
      this.modal.isOpen = true;
    },
    editRecord(record) {
      this.modal.data = { ...record };
      console.log(this.modal.data.options);
      // this.modal.data.options=JSON.parse(this.modal.data.options)
      if (this.modal.data.options == null) {
        this.modal.data.options = [{ value: "option_1", label: "option_1" }];
      }
      this.modal.mode = "EDIT";
      this.modal.isOpen = true;
    },
    onFormFinish() {
      this.$refs.modalRef
        .validateFields()
        .then(() => {
          if (this.modal.mode == "CREATE") {
            this.storeRecord(this.modal.data);
            this.modal.isOpen = false;
          }
          if (this.modal.mode == "EDIT") {
            this.updateRecord(this.modal.data);
            this.modal.isOpen = false;
          }
        })
        .catch((err) => {
          console.log("error", err);
        });
    },
    storeRecord(data) {
      this.$inertia.post(
        route("manage.form.fields.store", {
          form: data.form_id,
        }),
        data,
        {
          onSuccess: (page) => {
            data = {};
          },
          onError: (err) => {
            console.log(err);
          },
        }
      );
    },
    updateRecord(data) {
      this.$inertia.patch(
        route("manage.form.fields.update", {
          form: data.form_id,
          field: data,
        }),
        data,
        {
          onSuccess: (page) => {
            data = {};
            console.log(page);
          },
          onError: (error) => {
            console.log(error);
          },
        }
      );
    },
    deleteRecord(record) {
      if (!confirm("Are you sure want to remove?")) return;
      this.$inertia.delete(
        route("form.fields.destroy", {
          form: this.form.id,
          field: record.id,
        }),
        {
          onSuccess: (page) => {
            console.log("the field has been deleted!");
          },
          onError: (error) => {
            alert(error.message);
          },
        }
      );
    },
    addOptionItem() {
      const newOption = "option_" + (this.modal.data.options.length + 1);
      this.modal.data.options.push({ value: newOption, label: newOption });
    },
    onChangeOptionTemplate(value) {
      this.modal.data.options = this.templateOptions.find(
        (t) => t.value == value
      ).template;
    },
    onChangeType(value) {
      if (["textarea", "longtext", "richtext"].includes(this.modal.data.type)) {
        if (!Number.isInteger(this.modal.data.type)) {
          this.modal.data.options = 5;
        }
      } else if (["radio", "checkbox"].includes(this.modal.data.type)) {
        if (typeof this.modal.data.options !== "object") {
          this.modal.data.options = [{ value: "option_1", label: "option_1" }];
        }
      }
    },
    onFieldLabelChanged(value) {
      if (!this.modal.data.field_name) {
        this.modal.data.field_name = this.modal.data.field_label;
      }
    },
    rowChange(event) {
      var data = [];
      this.fields.forEach((field, idx) => {
        data.push({ id: field.id, form_id: field.form_id, sequence: idx });
      });
      console.log(data);
      this.$inertia.post(route("manage.form.fieldsSequence", this.form.id), data, {
        onSuccess: (page) => {
          console.log(page);
        },
        onError: (error) => {
          console.log(error);
        },
      });
    },
  },
};
</script>
