<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
          <div class="card animated fadeIn w-90 p-4">
            <form @submit.prevent="verifyOtp">
              <div class="card-body">
                <h4>ENTER OTP CODE</h4>
                <label>4 Digit Code Here</label>
                <input
                  v-model="form.otp"
                  placeholder="Code"
                  class="form-control"
                  type="text"
                />
                <br />
                <button type="submit" class="btn w-100 btn-success">Next</button>
  
                <!-- Optional flash messages -->
                <p v-if="form.errors.otp" class="text-danger mt-2">{{ form.errors.otp }}</p>
                <p v-if="$page.props.flash.message" class="text-success mt-2">
                  {{ $page.props.flash.message }}
                </p>
                <p v-if="$page.props.flash.error" class="text-danger mt-2">
                  {{ $page.props.flash.error }}
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  

  <script setup>
  import { useForm, router } from '@inertiajs/vue3'
  
  const form = useForm({
    otp: ''
  })
  
  function verifyOtp() {
    form.get('/verify-otp', {
      onSuccess: () => {
        form.reset()
  
      },
      onError: (errors) => {
        console.log('OTP verification error:', errors)
      }
    })
  }
  </script>
  