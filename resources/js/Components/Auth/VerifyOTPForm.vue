<template>

  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-7 col-lg-6 center-screen">
              <div class="card animated fadeIn w-90  p-4">
                  <div class="card-body">
                      <h4>ENTER OTP CODE</h4>
                      <br/>
                      <label>4 Digit Code Here</label>
                      <input id="otp" placeholder="Code" class="form-control" type="text"/>
                      <br/>
                      <Link href="/ResetPasswordPage"  class="btn w-100 btn-success">Next</Link>
                  </div>
              </div>
          </div>
      </div>
  </div>

</template>



<script setup>
import { Link } from '@inertiajs/vue3'
</script>

