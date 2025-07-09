<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="card-body">
                                <h4>
                                    {{
                                        id !== 0
                                            ? "Update Category"
                                            : "Create Customer"
                                    }}
                                </h4>
                                <input
                                    v-if="id !== 0"
                                    id="id"
                                    name="id"
                                    v-model="form.id"
                                    placeholder="Category ID"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <input
                                    id="name"
                                    name="name"
                                    v-model="form.name"
                                    placeholder="Product Name"
                                    class="form-control"
                                    type="text"
                                    required
                                />
                                <br />

                                <select
                                    v-model="form.category_id"
                                    class="form-control"
                                    required
                                >
                                    <option disabled value="">
                                        Select A Category
                                    </option>
                                    <option
                                        v-for="item in category"
                                        :key="item.id"
                                        :value="item.id"
                                    >
                                        {{ item.name }}
                                    </option>
                                </select>
                                <br />
                                <input
                                    id="price"
                                    name="price"
                                    v-model="form.price"
                                    placeholder="Product Price"
                                    class="form-control"
                                    type="text"
                                    required
                                />
                                <br />
                                <input
                                    id="unit"
                                    name="unit"
                                    v-model="form.unit"
                                    placeholder="Product Unit"
                                    class="form-control"
                                    type="text"
                                    required
                                />
                                <br />
                                <button
                                    type="submit"
                                    class="btn w-100 btn-success"
                                >
                                    {{
                                        id !== 0
                                            ? "Update Product"
                                            : "Add Product"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();

import { ref } from "vue";

const urlParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParams.get("id") || 0));
const page = usePage();
let URL = "/create-product";
let list = page.props.list;

let category = page.props.category;

if (id.value !== 0 && list !== null) {
    URL = "/update-product";
}

const form = useForm({
    name: list?.name || "",
    unit: list?.unit || "",
    category_id: list?.category_id || "",
    price: list?.price || "",

    id: id,
});

function submit() {
    form.post(URL, {
        onSuccess: () => {
            if (id.value !== 0) {
                toaster.success("Product Updated");

                router.visit("/ProductPage");
            } else {
                toaster.success("Product Added");
                form.reset();
            }
        },
        onError: () => {
            toaster.error("Something Went wrong");
        },
    });
}
</script>
