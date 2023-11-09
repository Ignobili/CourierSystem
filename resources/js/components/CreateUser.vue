<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="login-form border-right mt-4 p-4">
                    <form class="row g-3">
                        <h4 class="text-center">Vytvorenie používateľa na základe jeho požiadaviek</h4>
                        <div class="col-12">
                            <label for="name">Používateľské meno</label>
                            <input type="hidden" name="_token" value="">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Používateľské meno" required="required" v-model="name">
                        </div>
                        <div class="col-12">
                            <label for="email">Používateľov funkčný e-mail</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="E-mail" required="required" v-model="email">
                        </div>
                        <div class="col-12">
                            <label for="password">Heslo</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Heslo" required="required" v-model="password">
                        </div>
                        <div class="col-12 text-center">
                            <input class="btn btn-primary" type="submit" value="Vytvoriť" @click="handleCreateUser">
                        </div>
                    </form>
                    <div class="col-12 text-center mt-3">
                        <button class="btn btn-info" @click="$router.push('users_overview')">Späť na prehľad používateľov</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                name: "",
                email: "",
                password: "",
                error: null
            }
        },
        methods: {
            handleCreateUser(e) {
                e.preventDefault()
                if (this.password.length > 0) {
                    this.$axios.get('./sanctum/csrf-cookie').then(response => {
                        this.$axios.post('./api/register', {
                            name: this.name,
                            email: this.email,
                            password: this.password
                        })
                            .then(response => {
                                if (response.data.success) {
                                    Swal.fire({
                                        title: "Registrácia používateľa",
                                        text: "Používateľ bol úspešne zaregistrovaný.",
                                        icon: 'success'
                                    });
                                    //window.location.href = "/login"
                                } else {
                                    this.error = response.data.message
                                }
                            })
                            .catch(function (error) {
                                console.error(error);
                                Swal.fire({
                                    title: "Registrácia používateľa",
                                    text: error,
                                    icon: 'warning'
                                });
                            });
                    })
                }
            }
        }
    }

</script>

<style>
</style>
