<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
    </template>
    <button
      @click="createRecord()"
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3"
    >
      Create User
    </button>
    <a-table :dataSource="users" :columns="columns">
      <template #bodyCell="{ column, text, record, index }">
        <template v-if="column.dataIndex == 'operation'">
          <a-button @click="editRecord(record)">Edit</a-button>
          <a-button @click="deleteRecord(record.id)">Delete</a-button>
        </template>
        <template v-else-if="column.dataIndex == 'organizations'">
            <ol class="list-decimal">
                    <li v-for="organization in record['organizations']">
                    {{ organization.abbr }}
                </li>
            </ol>
        </template>
        <template v-else>
          {{ record[column.dataIndex] }}
        </template>
      </template>
    </a-table>

    <!-- Modal Start-->
    <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%">
      <a-form
        ref="modalRef"
        :model="modal.data"
        name="Teacher"
        :label-col="{ span: 8 }"
        :wrapper-col="{ span: 16 }"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
      >
        <a-input type="hidden" v-model:value="modal.data.id" />
        <a-form-item label="用戶名稱" name="name">
          <a-input v-model:value="modal.data.name" />
        </a-form-item>
        <a-form-item label="電郵" name="email">
          <a-input v-model:value="modal.data.email" />
        </a-form-item>
        <a-form-item label="密碼" name="password" v-if="modal.mode=='CREATE'">
          <a-input v-model:value="modal.data.password" />
        </a-form-item>
        <a-form-item label="屬會" name="organization_ids">
            <a-select v-model:value="modal.data.organization_ids"
                mode="multiple"
                :options="organizations"
                :fieldNames="{value:'id',label:'full_name'}"
            />
        </a-form-item>
      </a-form>
      <template #footer>
        <a-button
          v-if="modal.mode == 'EDIT'"
          key="Update"
          type="primary"
          @click="updateRecord()"
          >Update</a-button
        >
        <a-button
          v-if="modal.mode == 'CREATE'"
          key="Store"
          type="primary"
          @click="storeRecord()"
          >Add</a-button
        >
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
  props: ["organizations","users"],
  data() {
    return {
      modal: {
        isOpen: false,
        data: {},
        title: "Modal",
        mode: "",
      },
      teacherStateLabels: {},
      columns: [
        {
          title: "Username",
          dataIndex: "name",
        },{
          title: "Email",
          dataIndex: "email",
        },{
          title: "Organizations",
          dataIndex: "organizations",
        },{
          title: "State",
          dataIndex: "state",
        },{
          title: "Operation",
          dataIndex: "operation",
          key: "operation",
        },
      ],
      rules: {
        name: { required: true },
        email:{ required: true,type:'email' },
        password: { required: true },
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
  methods: {
    createRecord() {
      this.modal.data = {};
      this.modal.mode = "CREATE";
      this.modal.title = "Create User";
      this.modal.isOpen = true;
    },
    editRecord(record) {
      this.modal.data = { ...record };
      this.modal.data.organization_ids=record.organizations.map(item=>(item.id));
      this.modal.mode = "EDIT";
      this.modal.title = "Edit User";
      this.modal.isOpen = true;
    },
    storeRecord() {
      this.$refs.modalRef
        .validateFields()
        .then(() => {
          this.$inertia.post("/admin/users/", this.modal.data, {
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
        this.$inertia.patch("/admin/users/" + this.modal.data.id, this.modal.data, {
        onSuccess: (page) => {
            this.modal.data = {};
            this.modal.isOpen = false;
            console.log(page);
        },
        onError: (error) => {
            console.log(error);
        },
        });
    },
    deleteRecord(recordId) {
      if (!confirm("Are you sure want to remove?")) return;
      this.$inertia.delete("/admin/users/" + recordId, {
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