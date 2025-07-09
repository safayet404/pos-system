<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="d-flex justify-content-between py-2">
                                <h3>Category List</h3>
                                <Link
                                    class="start-btn btn btn-success"
                                    href="/category-save"
                                    >Category Create</Link
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
                                <template #item-number="{ id, player }">
                                    <Link
                                        class="btn btn-success mx-3 btn-sm"
                                        :href="`/category-save?id=${id}`"
                                        >Edit</Link
                                    >
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
import CategoryCreateForm from "./CategoryCreateForm.vue";
const page = usePage();
const category = page?.props?.list;

const searchValue = ref("");
const searchField = "name";
const Header = [
    { text: "No", value: "rowNumber" },
    { text: "Name", value: "name" },
    { text: "Action", value: "number" },
];

const Item = ref(
    category.map((item, index) => ({
        ...item,
        rowNumber: index + 1,
    }))
);

const DeleteClick = (id) => {
    let text = "Do you want to delete";
    if (confirm(text) === true) {
        router.post(
            `/delete-category/${id}`,
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    Item.value = Item.value.filter((item) => item.id !== id);
                },
            }
        );
    } else {
        text = "You canceled !";
    }
};

const itemClick = (number, player) => {
    alert(`Number is=${number} & Player Name is=${player}`);
};
</script>
