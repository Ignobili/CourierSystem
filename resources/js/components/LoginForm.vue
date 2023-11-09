<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="login-form border-right mt-4 p-4">
                    <form method="post" class="row g-3">
                        <h4 class="text-center">Vitaj späť!</h4>
                        <div class="col-12">
                            <label for="email">E-mailová adresa</label>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="E-mailová adresa"
                                v-model="email"
                            />
                        </div>
                        <div class="col-12">
                            <label for="password">Heslo</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Heslo"
                                v-model="password"
                            />
                        </div>
                        <div class="col-12 col-md-4 offset-4">
                            <button
                                type="submit"
                                value="Prihlásiť sa"
                                class="btn btn-primary"
                                @click="handleLogin"
                            >Prihlásiť sa</button>
                        </div>
                    </form>
                    <hr class="mt-4" />
                    <div class="col-12">
                        <p class="text-center mb-0">
                            Nemáte účet?
                            <router-link :to="{ name: 'register' }">Registrovať sa</router-link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";

export default {
    data() {
        return {
            email: "",
            password: "",
            error: null
        }
    },
    methods: {
        handleLogin(e) {
            e.preventDefault()
            if (this.password.length > 0) {
                this.$axios.get('./sanctum/csrf-cookie').then(response => {
                    this.$axios.post('./api/login', {
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            console.log(response.data)
                            if (response.data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Úspech!',
                                    text: 'Si prihlásený. Klikni na OK pre pokračovanie',
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "./"
                                    } else {
                                        window.location.href = "./"
                                    }
                                });
                            } else {
                                this.error = response.data.message
                                Swal.fire({
                                    title: "Prihlásenie",
                                    text: "Zadali ste neplatné prihlasovacie údaje.",
                                    icon: 'warning'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            Swal.fire({
                                title: "Prihlásenie",
                                text: 'Účet bol zablokovaný na 1 hodinu. Príliš veľa pokusov o prihlásenie !',
                                icon: 'warning'
                            });
                        });
                })
            }
        }
    },
    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next({ name: 'home' });
        }
        next();
    }
}
</script>
