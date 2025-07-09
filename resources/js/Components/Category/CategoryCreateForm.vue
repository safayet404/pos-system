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
                                            : "Create Category"
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
                                    placeholder="Category Name"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <button
                                    type="submit"
                                    class="btn w-100 btn-success"
                                >
                                    {{
                                        id !== 0
                                            ? "Update Category"
                                            : "Add Category"
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
import InvoiceList from "../Invoice/InvoiceList.vue";

const urlParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParams.get("id") || 0));
const page = usePage();
let URL = "/create-category";
let list = page.props.list;

if (id.value !== 0 && list !== null) {
    URL = "/update-category";
}

const form = useForm({
    name: list?.name || "",
    id: id,
});

function submit() {
    form.post(URL, {
        onSuccess: () => {
            if (id.value !== 0) {
                toaster.success("Category Updated");
                router.visit("/CategoryPage");
            } else {
                toaster.success("Category Updated");
                form.reset();
            }
        },
    });
}
</script>
