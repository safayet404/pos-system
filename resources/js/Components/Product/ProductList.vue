
<template>
 
  <div class="container-fluid">
   <div class="row">
     <div class="col-12">
       <div class="card">
         <div class="card-body">
           <div>
             <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text" v-model="searchValue">
                         <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" :rows-per-page="10" :search-field="searchField" :search-value="searchValue">
               <template #item-number="{ number,player }">
                 <button class="btn btn-success mx-3 btn-sm" @click="itemClick(3,player)">Edit</button>
                 <button class="btn btn-danger mx-3 btn-sm" @click="itemClick(number,player)">Delete</button>
               
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
onMounted(async () => {
  try {
    const res = await fetch('/list-product')
    if (!res.ok) throw new Error('Failed to fetch product')
    const products = await res.json()

    Item.value = products.map((item, index) => ({

      id: item.id,
      no : index + 1,
      name: item.name,
      category: item.category.name,
      price: item.price,
      unit : item.unit

    }))
    
    
  } catch (e)
  {
    console.log("Error fetching products",error)
  }
 })
 
 
 const itemClick = (number,player) => {
     alert(`Number is=${number} & Player Name is=${player}`)
 }
 
 
 </script>