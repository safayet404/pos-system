<template>
    <div class="modal fade" tabindex="-1" ref="modalRef">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invoice</h5>
                    <button
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div id="invoice" class="modal-body p-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-8">
                                <p><strong>Billed To</strong></p>
                                <p>Name: {{ customer.name }}</p>
                                <p>Email: {{ customer.email }}</p>
                                <p>User ID: {{ customer.user_id }}</p>
                            </div>
                            <div class="col-4 text-end">
                                <img
                                    class="w-50"
                                    src="../../Assets/img/logo.png"
                                />
                                <p><strong>Invoice</strong></p>
                                <p>Date: {{ today }}</p>
                            </div>
                        </div>
                        <hr />
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in products" :key="i">
                                    <td>{{ item.product.name }}</td>
                                    <td>{{ item.qty }}</td>
                                    <td>{{ item.sale_price }}</td>
                                    <td>{{ item?.qty * item.sale_price }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr />
                        <p><strong>Total:</strong> ${{ invoice.total }}</p>
                        <p><strong>VAT:</strong> ${{ invoice.vat }}</p>
                        <p>
                            <strong>Discount:</strong> ${{ invoice.discount }}
                        </p>
                        <hr />
                        <p><strong>Payable:</strong> ${{ invoice.payable }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button class="btn btn-success" @click="printInvoice">
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Modal } from "bootstrap";

const modalRef = ref(null);
let modalInstance = null;

const customer = ref({});
const invoice = ref({});
const products = ref([]);
const today = new Date().toISOString().split("T")[0];

onMounted(() => {
    modalInstance = new Modal(modalRef.value);
});

const openModal = async (cus_id, inv_id) => {
    try {
        const response = await fetch("/invoice-details", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ cus_id, inv_id }),
        });

        if (!response.ok) {
            throw new Error("Failed to fetch invoice");
        }

        const data = await response.json();

        customer.value = data.customer;
        invoice.value = data.invoice;
        products.value = data.product;

        modalInstance.show();
    } catch (error) {
        console.error("Fetch Error:", error);
    }
};

function printInvoice() {
    const invoiceHTML = document.getElementById("invoice").innerHTML;
    const win = window.open("", "_blank", "width=800,height=600");

    win.document.write(`
    <html>
      <head>
        <title>Invoice</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      </head>
      <body onload="window.print(); window.close();">
        ${invoiceHTML}
      </body>
    </html>
  `);

    win.document.close();
}

defineExpose({ openModal });
</script>
