<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import { Modal } from "bootstrap";
import SideNavLayout from "../Layouts/SideNavLayout.vue";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();
const page = usePage();
const customers = page?.props?.customers;
const products = page?.props?.products;
const customerSearchValue = ref("");
const customerSearchField = "name";

// For Product Search
const productSearchValue = ref("");
const productSearchField = "name";

const CustomerHeader = [
    { text: "Name", value: "name" },
    { text: "Action", value: "number" },
];
const ProductHeader = [
    { text: "Name", value: "name" },
    { text: "Price", value: "price" },
    { text: "Action", value: "number" },
];

// Format customers and products for the data table
const Item = ref(customers);
const Product = ref(products);
// Search/filter for EasyDataTable

// Invoice data
const invoiceItems = ref([]);
const selectedCustomer = ref(null);
const discountPercentage = ref(0);

// Product modal logic
const currentProduct = ref(null);
const qty = ref(1);
const productModal = ref(null);
let productModalInstance = null;

// Pick customer
function selectCustomer(customer) {
    selectedCustomer.value = customer;
}

// Open modal with product data
function openModal(product) {
    currentProduct.value = product;

    // Check if product already in invoice
    const existing = invoiceItems.value.find(
        (item) => item.product_id === product.id
    );

    qty.value = existing ? existing.qty : 1;

    if (productModalInstance) productModalInstance.show();
}

// Add selected product to invoice
function addProductToInvoice() {
    if (!currentProduct.value || qty.value < 1) return;

    const totalPrice = (
        parseFloat(currentProduct.value.price) * qty.value
    ).toFixed(2);

    const existingIndex = invoiceItems.value.findIndex(
        (item) => item.product_id === currentProduct.value.id
    );

    if (existingIndex !== -1) {
        // Update existing product entry
        invoiceItems.value[existingIndex].qty = qty.value;
        invoiceItems.value[existingIndex].sale_price = totalPrice;
    } else {
        // Add new product
        invoiceItems.value.push({
            product_id: currentProduct.value.id,
            product_name: currentProduct.value.name,
            qty: qty.value,
            sale_price: totalPrice,
            unit_price: currentProduct.value.price,
        });
    }

    productModalInstance.hide();
}

// Mount Bootstrap modal
onMounted(() => {
    productModalInstance = new Modal(productModal.value);
});

// Invoice calculations
const total = computed(() =>
    invoiceItems.value
        .reduce((sum, item) => sum + parseFloat(item.sale_price), 0)
        .toFixed(2)
);

const discount = computed(() =>
    ((total.value * discountPercentage.value) / 100).toFixed(2)
);

const vat = computed(() =>
    (((total.value - discount.value) * 5) / 100).toFixed(2)
);

const payable = computed(() =>
    (total.value - discount.value + parseFloat(vat.value)).toFixed(2)
);

function submit() {
    const invoiceData = {
        total: total.value,
        discount: discount.value,
        vat: vat.value,
        payable: payable.value,
        customer_id: selectedCustomer.value?.id,
        products: invoiceItems.value.map((item) => ({
            product_id: item?.product_id,
            qty: item?.qty,
            sale_price: item?.unit_price,
        })),
    };

    router.post("/create-invoice", invoiceData, {
        onSuccess: () => {
            router.visit("/InvoiceListPage");
            toaster.success("Invoice Added");
        },
    });
}
</script>

<template>
    <SideNavLayout>
        <div class="container-fluid">
            <div class="row">
                <!-- Invoice Panel -->
                <div class="col-6">
                    <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                        <div class="row">
                            <div class="col-8">
                                <span class="fw-bold">BILLED TO</span>
                                <p>Name: {{ selectedCustomer?.name || "-" }}</p>
                                <p>
                                    Email: {{ selectedCustomer?.email || "-" }}
                                </p>
                                <p>
                                    User ID: {{ selectedCustomer?.id || "-" }}
                                </p>
                            </div>
                            <div class="col-4 text-end">
                                <img
                                    class="w-50"
                                    src="../Assets/img/logo.png"
                                />
                                <p class="fw-bold">Invoice</p>
                                <p>
                                    Date:
                                    {{ new Date().toISOString().split("T")[0] }}
                                </p>
                            </div>
                        </div>
                        <hr />
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in invoiceItems"
                                    :key="index"
                                >
                                    <td>{{ item.product_name }}</td>
                                    <td>{{ item.qty }}</td>
                                    <td>${{ item.sale_price }}</td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-danger"
                                            @click="
                                                invoiceItems.splice(index, 1)
                                            "
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr />
                        <div>
                            <p>Total: ${{ total }}</p>
                            <p>Discount: ${{ discount }}</p>
                            <p>VAT (5%): ${{ vat }}</p>
                            <p>Payable: ${{ payable }}</p>
                            <label>Discount (%):</label>
                            <input
                                v-model="discountPercentage"
                                type="number"
                                min="0"
                                step="0.25"
                                class="form-control mt-3 w-50"
                            />
                        </div>
                        <button
                            type="submit"
                            @click.prevent="submit"
                            class="btn btn-dark mt-3"
                        >
                            Confirm
                        </button>
                    </div>
                </div>

                <!-- Product Panel -->
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 mt-2">
                                <h3>Products</h3>
                                <input
                                    placeholder="Search products..."
                                    class="form-control mb-2 w-auto form-control-sm"
                                    type="text"
                                    v-model="productSearchValue"
                                />
                            </div>

                            <EasyDataTable
                                buttons-pagination
                                alternating
                                :headers="ProductHeader"
                                :items="Product"
                                :rows-per-page="10"
                                :search-field="productSearchField"
                                :search-value="productSearchValue"
                            >
                                <template #item-number="{ id, name, price }">
                                    <button
                                        class="btn btn-sm btn-dark"
                                        @click="openModal({ id, name, price })"
                                    >
                                        Pick
                                    </button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>

                <!-- Customer Panel -->
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 mt-2">
                                <h3>Customers</h3>
                                <input
                                    placeholder="Search customers..."
                                    class="form-control mb-2 w-auto form-control-sm"
                                    type="text"
                                    v-model="customerSearchValue"
                                />
                            </div>
                            <EasyDataTable
                                buttons-pagination
                                alternating
                                :headers="CustomerHeader"
                                :items="Item"
                                :rows-per-page="10"
                                :search-field="customerSearchField"
                                :search-value="customerSearchValue"
                            >
                                <template #item-number="{ id, name, email }">
                                    <button
                                        class="btn btn-sm btn-dark"
                                        @click="
                                            selectCustomer({ id, name, email })
                                        "
                                    >
                                        Pick
                                    </button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Modal -->
        <div
            class="modal fade"
            id="productModal"
            tabindex="-1"
            aria-labelledby="productModalLabel"
            aria-hidden="true"
            ref="productModal"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Add {{ currentProduct?.name }}
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Quantity</label>
                        <input
                            type="number"
                            class="form-control"
                            v-model="qty"
                            min="1"
                            placeholder="Enter quantity"
                        />
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            class="btn btn-success"
                            @click="addProductToInvoice"
                        >
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </SideNavLayout>
</template>
