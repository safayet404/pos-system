<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-between py-2">
                                <h3>Employee List</h3>
                                <Link
                                    class="start-btn btn btn-dark"
                                    href="/employee-save"
                                    >Employee Create</Link
                                >
                            </div>
                            <input
                                placeholder="Search..."
                                class="form-control mb-2 w-auto form-control-sm"
                                type="text"
                                v-model="searchValue"
                            />
                            <EasyDataTable
                                buttons-pagination
                                alternating
                                :headers="Header"
                                :items="Item"
                                :rows-per-page="10"
                                :search-field="searchField"
                                :search-value="searchValue"
                            >
                                <template #item-number="{ id }">
                                    <Link
                                        class="btn btn-dark mx-3 btn-sm"
                                        :href="`/employee-save/?id=${id}`"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        class="btn btn-danger mx-3 btn-sm"
                                        @click="DeleteClick(id)"
                                    >
                                        Delete
                                    </button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster();

const page = usePage();
const customer = page?.props?.list;
console.log("customers", customer);

const searchValue = ref("");
const searchField = "name";
const Header = [
    { text: "No", value: "rowNumber" },
    { text: "Name", value: "name" },
    { text: "Email", value: "email" },
    { text: "Mobile", value: "mobile" },
    { text: "Action", value: "number" },
];

const Item = ref(
    customer.map((item, index) => ({
        ...item,
        rowNumber: index + 1,
    }))
);

const DeleteClick = (id) => {
    let text = "Do you want to delete";

    if (confirm(text) === true) {
        router.post(
            `/delete-customer/${id}`,
            {},
            {
                onSuccess: () => {
                    toaster.success("Customer Deleted Successfully");
                    Item.value = Item.value.filter((item) => item.id !== id);
                },
                onError: () => {
                    toaster.error("Something went wrong");
                },
            }
        );
    }
};
</script>
