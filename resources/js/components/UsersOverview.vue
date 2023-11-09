<template>
    <div class="container-xl p-3">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h2>Prehľad používateľov evidovaných v systéme</h2>
                <p class="small">Počet používateľov: {{ usersCount }}</p>
            </div>
            <div class="col-4">
                <router-link :to="{ name: 'create_user' }" class="btn btn-success float-end">Vytvoriť používateľa</router-link>
            </div>
        </div>
        <div class="alert alert-dismissible alert-warning" shown="no">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <h4 class="alert-heading"><!-- alert title --></h4>
            <p class="mb-0"><!-- alert title --></p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-8 col-sm-6">
                <table class="table table-hover table-striped table-responsive-sm">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Prihlasovacie meno</th>
                        <th class="col">E-mail</th>
                        <td class="col">Vytvorený<!-- dátum a čas --></td>
                        <td class="col">Upravý<!-- dátum a čas --></td>
                        <th class="float-right">Akcie</th>
                    </tr>
                    </thead>
                    <tbody id="users-list-tbody">
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ formatDate(user.created_at) }}</td>
                        <td>{{ formatDate(user.updated_at) }}</td>
                        <td>
                            <!-- <button class="btn btn-info me-1">Upraviť</button> -->
                            <span v-if="user.id === 1"><button class="btn btn-danger" disabled>Vymazať</button></span>
                            <span v-else><button class="btn btn-danger" @click="handleUserRemoval(user.id, user.name, user.email)">Vymazať</button></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
import Swal from "sweetalert2";

export default {

    data() {
        return {
            users: [],
            usersCount: -1
        }
    },

    mounted: function() {
        this.$nextTick(function()
        {

            this.$axios.get('./sanctum/csrf-cookie').then(response => {
                this.$axios.post('./api/users-list')
                    .then(response => {

                        if (response.data)
                        {
                            this.users = response.data;
                            this.usersCount = this.users.length;
                        }
                        else
                        {
                            Swal.fire({title: "Zoznam používateľov", text: "Chyba!", icon: "warning", showDenyButton: false, confirmButtonText: "Schovať", denyButtonText: ""});
                        }

                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire({title: "Zoznam používateľov", text: "Chyba:\n" + error, icon: "warning", showDenyButton: false, confirmButtonText: "Schovať", denyButtonText: ""});
                    });
            })

        })
    },

    methods: {
        formatDate(dateString) {
            const date = new Date(dateString);
            // Then specify how you want your dates to be formatted
            return new Intl.DateTimeFormat('sk-SK', { dateStyle: 'short', timeStyle: 'short' }).format(date);
        },
        handleUserRemoval(id, name, email)
        {
            Swal.fire({
                title: "Odstránenie používateľa",
                text: `Naozaj chcete odstrániť používateľa '${name}' s e-mailovou adresou '${email}' ?`,
                icon: "question",
                iconColor: "#ff0000",
                confirmButtonText: 'Áno, odstrániť',
                showDenyButton: true,
                denyButtonText: 'Nie, neodstraňovať',
                focusDeny: true,
                customClass: {
                    confirmButton: 'btn btn-danger me-1',
                    denyButton: 'btn btn-success'
                },
                buttonsStyling: false
            }).then((result) => {

                if (result.isConfirmed)
                {

                    this.$axios.delete(`./api/user-removal/${id}`)
                        .then(response => {
                            // console.log(response.data)

                            if (response.data.success)
                            {
                                Swal.fire({
                                    title: 'Používatelia - Úspech!',
                                    text: response.data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    showCancelButton: false,
                                    showDenyButton: false
                                }).then(() => {

                                    this.users.splice(this.users.indexOf(id), 1);
                                    // window.location.reload();

                                });
                            }
                            else
                            {
                                Swal.fire({
                                    title: "Používatelia - Neúspech!",
                                    text: response.data.message,
                                    icon: 'warning'
                                });

                            }
                        })
                        .catch(function (error) {
                            // console.error(error)

                            Swal.fire({
                                title: "Používatelia - Chyba!",
                                text: "Chyba: " + error,
                                icon: 'error'
                            });

                        })
                }
                else if (result.isDenied)
                {
                    Swal.clickCancel();
                }

            })
        }

    }

}

</script>

<style>
</style>
