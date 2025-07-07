<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
          <div class="card animated fadeIn w-90 p-4">
            <form @submit.prevent="sendOtp">
              <div class="card-body">
                <h4>EMAIL ADDRESS</h4>
                <label>Your email address</label>
                <input
                  v-model="form.email"
                  placeholder="User Email"
                  class="form-control"
                  type="email"
                />
                <br />
                <button type="submit" class="btn w-100 btn-success">Next</button>
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
    email: ''
  })
  
  function sendOtp() {
    form.get('/send-otp', {
      onSuccess: () => {
        
        form.reset()
        router.get('/verify-otp-page') // Make sure this route exists
      },
      onError: (errors) => {
        console.error('OTP error:', errors)
      }
    })
  }
  </script>
  