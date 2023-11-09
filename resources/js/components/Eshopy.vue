<template>
    <div class="container-xl p-3">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h2>Zoznam E-Shopov v databáze</h2>
                <p class="small">Počet eshopov: {{ counter }}</p>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal"
                        data-bs-target="#createEshopModal">Vytvoriť E-Shop
                </button>
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
                        <th scope="col">Názov</th>
                        <th scope="col">Vytvorený</th>
                        <th scope="col">Upravený</th>
                        <th class="float-right">Akcie</th>
                    </tr>
                    </thead>
                    <tbody id="eshop-list-tbody">
                    <tr v-for="eshop in eshopy" :key="eshop.id">
                        <td>{{ eshop.id }}</td>
                        <td>{{ eshop.eshop_name }}</td>
                        <td>{{ formatDate(eshop.created_at) }}</td>
                        <td>{{ formatDate(eshop.updated_at) }}</td>
                        <td>
                            <router-link
                                :to="{
                                        name: 'editEshop',
                                        params: {
                                            id: eshop.id,
                                            eshop_name: eshop.eshop_name,
                                        },
                                    }"
                                type="button" class="btn btn-info me-1">Upraviť
                            </router-link>
                            <button class="btn btn-danger" @click="handleDeleteEshop(eshop.id, eshop.eshop_name)">Vymazať</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createEshopModal" tabindex="-1" aria-labelledby="eshopModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eshopModal">Vytvoriť E-Shop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="eshop-create-form border-right p-4">
                                    <form method="post" class="row g-3">
                                        <div class="col-12">
                                            <label for="eshopName" class="mb-2">Názov E-Shopu</label>
                                            <input
                                                type="text"
                                                id="eshopName"
                                                name="eshopName"
                                                class="form-control"
                                                placeholder="Alza.sk, Datart, CZC..."
                                                v-model="eshopName"
                                            />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">Zatvoriť</button>
                    <button type="button" class="btn btn-success" @click="handleInsertEshop">Vytvoriť</button>
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
            eshopName: "",
            eshopy: [],
            error: null,
            counter:''
        }
    },
    mounted: function () {
        this.$nextTick(function () {

            this.$axios.get('./sanctum/csrf-cookie').then(response => {
                this.$axios.post('./api/eshop-list')
                    .then(response => {

                        if (response.data) {
                            this.eshopy = response.data;
                            this.counter=this.eshopy.length;
                        } else {
                            Swal.fire({title: "Zoznam Eshopov", text: "Chyba!", icon: "warning"});
                        }

                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire({title: "Zoznam Eshopov", text: "Chyba:\n" + error, icon: "warning"});
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
        handleInsertEshop(e) {
            e.preventDefault()
            if (this.eshopName.length > 0) {
                this.$axios.get('./sanctum/csrf-cookie').then(response => {
                    this.$axios.post('./api/eshop-insert', {
                        eshopName: this.eshopName
                    })
                        .then(response => {
                            console.log(response.data)
                            if (response.data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Úspech!',
                                    text: response.data.message,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "./eshopy"
                                    } else {
                                        window.location.href = "./eshopy"
                                    }
                                });
                            } else {
                                this.error = response.data.message
                                Swal.fire({
                                    title: "Eshopy",
                                    text: response.data.message,
                                    icon: 'warning'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            Swal.fire({
                                title: "Eshopy",
                                text: error,
                                icon: 'warning'
                            });
                        });
                })
            }
        },
        handleDeleteEshop(id, name) {
            Swal.fire({
                icon: "warning",
                title: `Naozaj chceš vymazať EShop ${name}?`,
                showDenyButton: true,
                confirmButtonText: 'Vymazať',
                denyButtonText: `Nie, pomýlil som sa`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$axios.delete(`./api/eshop-delete/${id}`)
                        .then(response => {
                            console.log(response.data)
                            if (response.data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Úspech!',
                                    text: response.data.message,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.eshopy.splice(this.eshopy.indexOf(id), 1);
                                    } else {
                                        this.eshopy.splice(this.eshopy.indexOf(id), 1);
                                    }
                                });
                            } else {
                                this.error = response.data.message
                                Swal.fire({
                                    title: "Eshopy",
                                    text: response.data.message,
                                    icon: 'warning'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            Swal.fire({
                                title: "Eshopy",
                                text: error,
                                icon: 'warning'
                            });
                        });
                } else if (result.isDenied) {
                    Swal.clickCancel()
                }
            })
        }
    }
}

</script>

<style>

</style>
