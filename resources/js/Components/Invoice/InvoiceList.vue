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
                                    class="start-btn btn btn-success"
                                    :href="`/category-save/${id}`"
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
                                    #item-number="{ id, invoice_id, player }"
                                >
                                    <button
                                        class="btn btn-success mx-3 btn-sm"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
const page = usePage();
console.log("invoice", page?.props?.list);

import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();
const searchValue = ref("");
const searchField = "product";
const Header = [
    { text: "No", value: "no" },
    //  { text: "ID", value: "id" },
    { text: "Name", value: "name" },
    { text: "Mobile", value: "mobile" },
    //  { text: "Product", value: "product"},
    //  { text: "Qty", value: "qty"},
    { text: "Sale Price", value: "price" },
    { text: "Discount", value: "discount" },
    //  { text: "Invoice ID", value: "invoice_id"},
    { text: "Vat", value: "vat" },
    { text: "Payable", value: "payable" },
    { text: "Action", value: "number" },
];

const Item = ref([]);
onMounted(async () => {
    try {
        const res = await fetch("/invoice-list");
        if (!res.ok) throw new Error("Failed to fetch customer");
        const invoices = await res.json();
        console.log("invoices", invoices);

        Item.value = invoices.map((item, index) => ({
            id: item.id,

            no: index + 1,
            name: item.invoice.customer.name,
            mobile: item.invoice.customer.mobile,

            price: item.sale_price,
            discount: item.invoice.discount,
            vat: item.invoice.vat,
            payable: item.invoice.payable,
        }));
    } catch (e) {
        console.log("Error fetching products", error);
    }
});

const DeleteClick = (id, invoice_id) => {
    let text = "Do you want to delete this?";

    if (confirm(text) === true) {
        router.post(
            `/delete-invoice/${invoice_id}`,
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
