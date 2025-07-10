<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-between py-2">
                                <h3>Invoice List</h3>
                                <Link
                                    class="start-btn btn btn-dark"
                                    href="/SalePage"
                                    >Create Sale</Link
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
                                <template
                                    #item-number="{ id, cus_id, invoice_id }"
                                >
                                    <button
                                        class="btn btn-dark mx-3 btn-sm"
                                        @click="openInvoice({ cus_id, id })"
                                    >
                                        <i class="fa fa-eye text-white" />
                                    </button>
                                    <button
                                        class="btn btn-dark mx-3 btn-sm"
                                        @click="itemClick(3, player)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="btn btn-danger mx-3 btn-sm"
                                        @click="DeleteClick(id, invoice_id)"
                                    >
                                        Delete
                                    </button>
                                </template>
                            </EasyDataTable>
                            <InvoiceModal ref="invoiceModal" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
const page = usePage();
console.log("invoice", page?.props?.list);
const invoiceList = page?.props?.list;
import { createToaster } from "@meforma/vue-toaster";
import InvoiceModal from "../Modal/InvoiceModal.vue";
const toaster = createToaster();
const searchValue = ref("");
const searchField = "product";
const Header = [
    { text: "No", value: "no" },
    //  { text: "ID", value: "id" },
    { text: "Name", value: "name" },
    { text: "Mobile", value: "mobile" },
    { text: "Total", value: "total" },
    //  { text: "Product", value: "product"},
    //  { text: "Qty", value: "qty"},
    // { text: "Sale Price", value: "price" },
    { text: "Discount", value: "discount" },
    //  { text: "Invoice ID", value: "invoice_id"},
    { text: "Vat", value: "vat" },
    { text: "Payable", value: "payable" },
    { text: "Action", value: "number" },
];

const Item = ref([]);
Item.value = invoiceList.map((item, index) => ({
    id: item.id,

    no: index + 1,
    cus_id: item?.customer_id,

    name: item?.customer?.name,
    mobile: item?.customer?.mobile,

    discount: item?.discount,
    total: item?.total,
    payable: item?.payable,

    vat: item?.vat,
}));

const invoiceModal = ref(null);

function openInvoice({ cus_id, id }) {
    invoiceModal.value.openModal(cus_id, id);
}

const DeleteClick = (id) => {
    let text = "Do you want to delete this?";

    if (confirm(text) === true) {
        router.post(
            `/delete-invoice/${id}`,
            {},
            {
                onSuccess: () => {
                    toaster.success("Invoice Deleted successfully!");
                    Item.value = Item.value.filter((item) => item.id !== id);
                },
            }
        );
    }
};

const itemClick = (number, player) => {
    alert(`Number is=${number} & Player Name is=${player}`);
};
</script>
