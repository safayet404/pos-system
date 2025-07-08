
<template>
 
  <div class="container-fluid">
   <div class="row">
     <div class="col-12">
       <div class="card">
         <div class="card-body">
           <div>
             <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text" v-model="searchValue">
                         <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" :rows-per-page="10" :search-field="searchField" :search-value="searchValue">
               <template #item-number="{ id,player }">
                 <button class="btn btn-success mx-3 btn-sm" @click="itemClick(3,player)">Edit</button>
                 <button class="btn btn-danger mx-3 btn-sm" @click="DeleteClick(id)">Delete</button>
               
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
 import { router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster()
const page = usePage()
const product = page?.props?.list
console.log('product',product);
 
 const searchValue = ref("");
const searchField = "name"; 

 const Header = [
     { text: "No", value: "no" },
     { text: "Name", value: "name"},
     { text: "Category", value: "category"},
     { text: "Unit", value: "unit"},
     { text: "Price", value: "price"},
     { text: "Action", value: "number"},
 ];
 
 
const Item = ref([])
Item.value = product.map((item, index) => ({

  id: item.id,

no : index + 1,
name: item.name,
category: item.category.name,
price: item.price,
unit : item.unit

}))

const DeleteClick = (id) => {
  let text = "Do you want to delete this?"

  if (confirm(text) === true)
  {
    router.post(`/delete-product/${id}`, {}, {
      onSuccess: () => {
        toaster.success("Product Deleted successfully!")
      Item.value = Item.value.filter(item => item.id !== id)
    }
  })
}
}

 
 const itemClick = (number,player) => {
     alert(`Number is=${number} & Player Name is=${player}`)
 }
 
 
 </script>