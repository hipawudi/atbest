<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $t("organizations") }}
      </h2>
    </template>
    <button @click="createRecord()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
      {{ $t("create_organization") }}
    </button>
    <div class="container mx-auto pt-5">
      <div class="bg-white relative shadow rounded-lg overflow-x-auto">
        <a-table :dataSource="organizations.data" :columns="columns" :pagination="pagination" @change="onPaginationChange">
          <template #headerCell="{ column }">
            {{ column.i18n ? $t(column.i18n) : column.title }}
          </template>
          <template #bodyCell="{ column, text, record, index }">
            <template v-if="column.dataIndex == 'operation'">
              <inertia-link
                :href="route('admin.organization.members', record.id)"
                class="ant-btn"
                >{{ $t("members") }}</inertia-link
              >
              <a-button @click="editRecord(record)">{{ $t("edit") }}</a-button>
              <a-popconfirm
                :title="$t('confirm_delete_record')"
                :ok-text="$t('yes')"
                :cancel-text="$t('no')"
                @confirm="deleteRecord(record)"
              >
                <a-button>{{ $t("delete") }}</a-button>
              </a-popconfirm>
              <a-button @click="masqueradeOrganization(record)" class="ant-btn">{{ $t('masquerade') }}..</a-button>

            </template>
            <template v-else-if="column.dataIndex == 'parish'">
              {{ getOptionLabel(parishes,text) }}
            </template>
            <template v-else-if="column.dataIndex == 'manager'">
              <ol class="list-decimal">
                <li v-for="user in record['users']">
                  {{ user.name }}
                </li>
              </ol>
            </template>
            <template v-else>
              {{ record[column.dataIndex] }}
            </template>
          </template>
        </a-table>
      </div>
    </div>
    <!-- Modal Start-->
    <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%">
      <a-form
        ref="modalRef"
        :model="modal.data"
        name="Organization"
        :label-col="{ span: 8 }"
        :wrapper-col="{ span: 16 }"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
        @finish="onFormFinish"
      >
        <a-form-item :label="$t('parish')" name="parish" :rules="[{ required: true }]">
          <a-select v-model:value="modal.data.parish" :options="parishes" :fieldNames="{value:'value',label:'label_'+$t('lang')}" />
        </a-form-item>
        <a-form-item
          :label="$t('abbreviation')"
          name="abbr"
          :rules="[{ required: true }]"
        >
          <a-input v-model:value="modal.data.abbr" />
        </a-form-item>
        <!-- <a-form-item
          :label="$t('territory')"
          name="territory"
        >
          <a-input v-model:value="modal.data.territory" />
        </a-form-item> -->
        <a-form-item :label="$t('name_zh')" name="name_zh">
          <a-input v-model:value="modal.data.name_zh" />
        </a-form-item>
        <a-form-item :label="$t('name_en')" name="name_en">
          <a-input v-model:value="modal.data.name_en" />
        </a-form-item>
        <a-form-item :label="$t('name_pt')" name="name_pt">
          <a-input v-model:value="modal.data.name_pt" />
        </a-form-item>
        <a-form-item :label="$t('email')" name="email">
          <a-input v-model:value="modal.data.email" />
        </a-form-item>
        <a-form-item :label="$t('phone')" name="phone">
          <a-input v-model:value="modal.data.phone" />
        </a-form-item>
        <a-form-item :label="$t('address')" name="address">
          <a-input v-model:value="modal.data.address" />
        </a-form-item>
        <a-form-item :label="$t('website')" name="href">
          <a-input v-model:value="modal.data.href" />
        </a-form-item>
        <a-form-item :label="$t('registration_code')" name="registration_code">
          <a-input v-model:value="modal.data.registration_code" />
        </a-form-item>
        <a-form-item :label="$t('avatar')" name="avatar">
          <a-input v-model:value="modal.data.avatar" />
        </a-form-item>
        <a-form-item :label="$t('card_style')" name="card_style">
          <a-select v-model:value="modal.data.card_style" :options="cardStyles" />
        </a-form-item>
        <a-form-item :label="$t('content')" name="content">
          <a-textarea v-model:value="modal.data.content" />
        </a-form-item>
        <a-form-item :label="$t('president')" name="president">
          <a-input v-model:value="modal.data.president" />
        </a-form-item>
        <a-form-item :label="$t('status')" name="status">
          <a-switch
            v-model:checked="modal.data.status"
            :checkedValue="1"
            :unCheckedValue="0"
          />
        </a-form-item>
        <a-form-item :label="$t('manager')" name="manager">
          <a-select
            v-model:value="modal.data.user_ids"
            mode="multiple"
            :options="modal.data.members.filter(m=>m.user_id!=null)"
            :fieldNames="{ value: 'user_id', label: 'given_name' }"
          />
        </a-form-item>
      </a-form>
      <template #footer>
        <a-button @click="$refs.modalRef.$emit('finish')" type="primary">
          <span v-if="modal.mode=='EDIT'">{{ $t('update') }}</span>
          <span v-if="modal.mode=='CREATE'">{{ $t('create') }}</span>
        </a-button>
      </template>
    </a-modal>
    <!-- Modal End-->
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, reactive } from "vue";

export default {
  components: {
    AdminLayout,
  },
  props: ["parishes","organizations", "users"],
  data() {
    return {
      modal: {
        isOpen: false,
        data: {},
        title: "Modal",
        mode: "",
      },
      pagination: {
        total: this.organizations.total,
        current: this.organizations.current_page,
        pageSize: this.organizations.per_page,
      },
      cardStyles:[],
      organizationStates: [
        { value: "ACTIVE", label: "Active" },
        { value: "SUSPENDED", label: "Suspended" },
      ],
      columns: [
        {
          title: "Parish",
          i18n: "parish",
          dataIndex: "parish",
        },{
          title: "Abbreviation",
          i18n: "abbreviation",
          dataIndex: "abbr",
        },{
          title: "Name",
          i18n: "name_zh",
          dataIndex: "name_zh",
        },{
          title: "Email",
          i18n: "email",
          dataIndex: "email",
        },{
          title: "Manager",
          i18n: "manager",
          dataIndex: "manager",
        },{
          title: "Operation",
          i18n: "operation",
          dataIndex: "operation",
          key: "operation",
        },
      ],
      rules: {
        name_zh: { required: true },
        title: { required: true },
        email: { required: true },
        mobile: { required: true },
        card_style: { required: true }
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
  created() {
    axios.get(route("api.config.item", { key: 'card_styles' })).then((resp) => {
            Object.entries(resp.data).forEach(([key, card]) => {
                this.cardStyles.push({ value: key, label: card.name })
            })
        });
  },
  methods: {
    onPaginationChange(page, filters, sorter) {
      this.$inertia.get(route("admin.organizations.index"), {
        page: page.current,
        per_page: page.pageSize,
      });
    },
    createRecord() {
      this.modal.data = {};
      this.modal.mode = "CREATE";
      this.modal.title = "Create Record";
      this.modal.isOpen = true;
    },
    editRecord(record) {
      this.modal.data = { ...record };
      this.modal.data.user_ids = record.users.map((item) => item.id);
      this.modal.mode = "EDIT";
      this.modal.title = "Edit Record";
      this.modal.isOpen = true;
    },
    onFormFinish(){
      this.$refs.modalRef
        .validateFields()
        .then(() => {
            if(this.modal.mode=='CREATE'){
              this.storeRecord(this.modal.data)
              this.modal.isOpen=false
            }
            if(this.modal.mode=='EDIT'){
              this.updateRecord(this.modal.data)
              this.modal.isOpen=false
            }
        })
        .catch((err) => {
          console.log("error", err);
        });
    },
    storeRecord() {
          this.$inertia.post(route("admin.organizations.store"), this.modal.data, {
            onSuccess: (page) => {
              this.modal.data = {};
              this.modal.isOpen = false;
            },
            onError: (err) => {
              console.log(err);
            },
          });
    },
    updateRecord() {
          this.$inertia.patch(
            route("admin.organizations.update", this.modal.data.id),
            this.modal.data,
            {
              onSuccess: (page) => {
                this.modal.isOpen = false;
                //this.modal.data = {};
              },
              onError: (error) => {
                console.log(error);
              },
            }
          );
    },
    deleteRecord(record) {
      this.$inertia.delete(route("admin.organizations.destroy", record.id), {
        onSuccess: (page) => {
          console.log(page);
        },
        onError: (error) => {
          console.log(error);
        },
      });
    },
    getOptionLabel(options, itemValue){
      if(options){
        const option=options.find(o=>o.value==itemValue)
        return option?option['label_'+this.$t('lang')]:null
      }
      return '--'
    },
    masqueradeOrganization(organization){
            this.$inertia.post(route('admin.organization.masquerade',{organization:organization.id}))
        }

  },
};
</script>
