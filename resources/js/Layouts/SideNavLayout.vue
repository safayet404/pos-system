<template>
  <div>
      <nav id="topNav" class="navbar fixed-top  top-navbar">
          <div class="container-fluid">
              <a id="MenuBar" @click="NavOpenClose" class="icon-nav mx-0 my-1 h5">
                  <i class="fa  fa-bars"></i>
              </a>
          </div>
      </nav>
      <div id="sideNav" class="side-nav-open">
          

          <Link href="/dashboard" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-tachometer-alt text-white"/></span>
              <span class="side-bar-item-caption">Dashboard</span>
          </Link>
          <Link href="/category-save" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-th-list text-white"/></span>
              <span class="side-bar-item-caption">Category Create</span>
          </Link>

          <Link href="/CategoryPage" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-th-list text-white"/></span>
              <span class="side-bar-item-caption">Category</span>
          </Link>
          
          <Link href="/customer-save" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-users text-white"/></span>
              <span class="side-bar-item-caption">Create Customer</span>
          </Link>
    <Link href="/CustomerPage" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-users text-white"/></span>
              <span class="side-bar-item-caption">Customer</span>
          </Link>

          <Link href="/ProductPage" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-box text-white"/></span>
              <span class="side-bar-item-caption">Product</span>
          </Link>

          <Link href="/SalePage" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-shopping-cart text-white"/></span>
              <span class="side-bar-item-caption">Create Sale</span>
          </Link>

          <Link href="/InvoiceListPage" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-file-invoice-dollar text-white"/></span>
              <span class="side-bar-item-caption">Invoice</span>
          </Link>

          <Link href="/profile" class="side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-user-circle text-white"/></span>
              <span class="side-bar-item-caption">Profile</span>
          </Link>

          <button @click="logout" class="reset-button side-bar-item">
              <span class="side-bar-item-icon"><i class="fa fa-sign-out-alt text-white"/></span>
              <span class="side-bar-item-caption">Logout</span>
          </button>





      </div>
      <div id="content" class="content">
          <div class="container-fluid">
              <main>
                  <slot></slot>
              </main>
          </div>
      </div>
  </div>
</template>


<script setup>
import { Link, router } from '@inertiajs/vue3'

function logout() {
    router.post('/logout', {}, {
        onSuccess: () => {
            router.visit('/login-page')
        }
    })
}
</script>

<style scoped>
.reset-button {
  all: unset;
  cursor: pointer;
  display: block;
  margin-left: 13px;
  width: 100%;
}
</style>