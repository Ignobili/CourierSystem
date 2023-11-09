<template>
    <div class="container-xl p-3">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h2>Zoznam kuriérov v databáze</h2>
                <p class="small">Počet kuriérov: {{ counter }}</p>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#createCourierModal">Vytvoriť kuriéra</button>
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
                        <th scope="col">Kód</th>
                        <th scope="col">E-Shop</th>
                        <th class="float-right">Akcia</th>
                    </tr>
                    </thead>
                    <tbody id="courier-list-tbody">
                    <tr v-for="courier in couriers" :key="courier.id">
                        <td>{{ courier.id }}</td>
                        <td>{{ courier.courier_name }}</td>
                        <td>{{ courier.courier_code }}</td>
                        <td>{{ courier.eshop_name }}</td>
                        <td>
                            <router-link
                                :to="{
                                        name: 'editCourier',
                                        params: {
                                            id: courier.id,
                                            courier_name: courier.courier_name,
                                            courier_code: courier.courier_code,
                                            eshop_id: courier.eshop_id,
                                        },
                                    }"
                                type="button"
                                class="btn btn-info me-1"
                            >Upraviť</router-link
                            >
                            <button
                                class="btn btn-danger"
                                @click="handleDeleteCourier(courier.id, courier.courier_name)"
                            >
                                Vymazať
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createCourierModal" tabindex="-1" aria-labelledby="courierModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="courierModal">Vytvoriť kuriéra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="courier-create-form border-right p-4">
                                    <form method="post" class="row g-3">
                                        <div class="col-12">
                                            <label for="courierName" class="mb-2">Názov kuriéra</label>
                                            <input
                                                type="text"
                                                id="courierName"
                                                name="courierName"
                                                class="form-control"
                                                placeholder="UPS, Slovenská pošta..."
                                                v-model="courierName"
                                            />
                                        </div>
                                        <div class="col-12">
                                            <label for="courierCode" class="mb-2">Kód kuriéra</label>
                                            <input
                                                type="text"
                                                id="courierCode"
                                                name="courierCode"
                                                class="form-control"
                                                placeholder="ups_courier_slovakia..."
                                                v-model="courierCode"
                                            />
                                        </div>
                                        <div class="col-12">
                                            <label for="eshopID" class="mb-2">E-Shop</label>
                                            <select
                                                class="form-select"
                                                aria-label="E-Shop"
                                                id="eshopID"
                                                v-model="eshopID"
                                            >
                                                <option
                                                    v-for="eshop in eshops"
                                                    :key="eshop.id"
                                                    v-bind:value="eshop.id"
                                                >
                                                    {{ eshop.eshop_name }}
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">Zatvoriť</button>
                    <button type="button" class="btn btn-success" @click="handleInsertCourier">Vytvoriť</button>
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
                courierName: "",
                courierCode: "",
                eshopID: "",
                eshops: [],
                couriers: [],
                error: null,
                counter: ''
            }
        },
        mounted: function () {
            this.$nextTick(function () {

                let local = this

                this.$axios.get('./sanctum/csrf-cookie').then(response => {
                    this.$axios.post('./api/courier-list')
                        .then(response => {

                            if (response.data) {
                                this.couriers = response.data;
                                this.counter = this.couriers.length;
                            } else {
                                Swal.fire({title: "Zoznam Kurierov", text: "Chyba!", icon: "warning"});
                            }

                        })
                        .catch(function (error) {
                            console.error(error);
                            Swal.fire({title: "Zoznam Kurierov", text: "Chyba:\n" + error, icon: "warning"});
                        });
                })
                this.$axios.get("./sanctum/csrf-cookie").then((response) => {
                    this.$axios
                        .post("./api/eshop-list")
                        .then((response) => {
                            if (response.data) {
                                this.eshops = response.data;
                                //    console.log(response.data);
                            } else {
                                console.log(error);
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                });

            })
        },

        methods: {
            handleUpdateCourier(id) {
                this.$axios.get(`./sanctum/csrf-cookie`).then((response) => {
                    this.$axios
                        .post(`./api/courier-update/${id}`)
                        .then((response) => {
                            if (response.data) {
                                this.couriers = response.data;
                                console.log(response.data);
                            } else {
                                console.log(error);
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                });
            },

            handleInsertCourier(e) {
                e.preventDefault()
                if (this.courierName.length || value.length > 0) {
                    this.$axios.get('./sanctum/csrf-cookie').then(response => {
                        this.$axios
                            .post('./api/courier-insert', {
                                courierName: this.courierName,
                                courierCode: this.courierCode,
                                eshopID: this.eshopID,
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
                                            window.location.href = "./kurieri"
                                        } else {
                                            window.location.href = "./kurieri"
                                        }
                                    });
                                } else {
                                    this.error = response.data.message
                                    Swal.fire({
                                        title: "Kurieri",
                                        text: response.data.message,
                                        icon: 'warning'
                                    });
                                }
                            })
                            .catch(function (error) {
                                console.error(error);
                                Swal.fire({
                                    title: "Kurieri",
                                    text: error,
                                    icon: 'warning'
                                });
                            });
                    })
                }
            },
            handleDeleteCourier(id, name) {
                Swal.fire({
                    icon: "warning",
                    title: `Naozaj chceš vymazať Kuriéra ${name}?`,
                    showDenyButton: true,
                    confirmButtonText: 'Vymazať',
                    denyButtonText: `Nie, pomýlil som sa`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$axios.delete(`./api/courier-delete/${id}`)
                            .then(response => {
                                console.log(response.data)
                                if (response.data.success) {
                                    this.counter--;
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Úspech!',
                                        text: response.data.message,
                                        showDenyButton: false,
                                        showCancelButton: false,
                                        confirmButtonText: 'OK',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            this.couriers.splice(this.couriers.indexOf(id), 1);
                                           // window.location.reload();
                                        } else {
                                            this.couriers.splice(this.couriers.indexOf(id), 1);
                                        }
                                    });
                                } else {
                                    this.error = response.data.message
                                    Swal.fire({
                                        title: "Kurieri",
                                        text: response.data.message,
                                        icon: 'warning'
                                    });
                                }
                            })
                            .catch(function (error) {
                                console.error(error);
                                Swal.fire({
                                    title: "Kurieri",
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
