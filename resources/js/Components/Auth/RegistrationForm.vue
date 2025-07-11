<script setup>
import { useForm, router, Link } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();
const form = useForm({
    email: "",
    name: "",
    mobile: "",
    password: "",
});
function submit() {
    form.post("/user-registration", {
        onSuccess: () => {
            form.reset();
            router.get("/login-page");
            toaster.success("User Registration Successfully");
        },
        onError: (errors) => {
            toaster.error("Something Went Wrong");
        },
    });
}
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-10 center-screen">
                <div class="card animated fadeIn w-100 p-3">
                    <form @submit.prevent="submit">
                        <div class="card-body">
                            <h4>Sign Up</h4>
                            <hr />
                            <div class="container-fluid m-0 p-0">
                                <div class="row m-0 p-0">
                                    <div class="col-md-4 p-2">
                                        <label>Name</label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            placeholder="Name"
                                            class="form-control"
                                            type="text"
                                            required
                                        />
                                    </div>

                                    <div class="col-md-4 p-2">
                                        <label>Email Address</label>
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            placeholder="User Email"
                                            class="form-control"
                                            type="email"
                                            required
                                        />
                                    </div>

                                    <div class="col-md-4 p-2">
                                        <label>Mobile Number</label>
                                        <input
                                            id="mobile"
                                            v-model="form.mobile"
                                            placeholder="Mobile"
                                            class="form-control"
                                            type="mobile"
                                            required
                                        />
                                    </div>

                                    <div class="col-md-4 p-2">
                                        <label>Password</label>
                                        <input
                                            id="password"
                                            v-model="form.password"
                                            placeholder="User Password"
                                            class="form-control"
                                            type="password"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="row m-0 p-0">
                                    <div class="col-md-4 gap-3 d-flex">
                                        <button
                                            type="submit"
                                            class="btn mt-3 w-100 btn-success"
                                        >
                                            {{
                                                form.processing
                                                    ? "Registering..."
                                                    : "Complete"
                                            }}
                                        </button>

                                        <Link
                                            class="btn mt-3 w-100 btn-success"
                                            href="/login-page"
                                        >
                                            Existing User
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
