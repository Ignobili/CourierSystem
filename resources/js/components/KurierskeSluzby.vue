<template>
    <div class="container-xl p-3">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h2>Zoznam kuriérskych služieb v databáze</h2>
                <p class="small">Počet služieb: {{ counter }}</p>
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#createServiceModal">Vytvoriť službu</button>
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
                        <th scope="col">Kuriér</th>
                        <th scope="col">Služba</th>
                        <th scope="col">Kód</th>
                        <th class="float-right">Akcie</th>
                    </tr>
                    </thead>
                    <tbody id="service-list-tbody">
                    <tr v-for="service in services" :key="service.id">
                        <td>{{ service.id }}</td>
                        <td>{{ service.courier_name }}</td>
                        <td>{{ service.service_name }}</td>
                        <td>{{ service.service_code }}</td>
                        <td class="float-right">
                        <router-link
                                    :to="{
                                        name: 'editService',
                                        params: {
                                            id: service.id,
                                            service_name: service.service_name,
                                            service_code: service.service_code,
                                            courier_id: service.courier_id
                                        }
                                    }"
                                    type="button" class="btn btn-info me-1">Upraviť
                                    </router-link>
                            <button class="btn btn-danger" @click="handleDeleteService(service.id, service.service_name, service.courier_name)">Vymazať</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="serviceModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceModal">Vytvoriť službu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="service-create-form border-right p-4">
                                    <form method="post" class="row g-3">
                                        <div class="col-12">
                                            <label for="serviceName" class="mb-2">Názov služby</label>
                                            <input
                                                type="text"
                                                id="serviceName"
                                                name="serviceName"
                                                class="form-control"
                                                placeholder="Doručenie nad 50kg celé Slovensko..."
                                                v-model="serviceName"
                                            />
                                        </div>
                                        <div class="col-12">
                                            <label for="serviceCodename" class="mb-2">Kód služby</label>
                                            <input
                                                type="text"
                                                id="serviceCodename"
                                                name="serviceCodename"
                                                class="form-control"
                                                placeholder="UPS24HMT, GLS48HS..."
                                                v-model="serviceCode"
                                            />
                                        </div>
                                        <div class="col-12">
                                             <label for="courierID" class="mb-2"
                                            >Kuriér</label
                                        >
                                        <select
                                            class="form-select"
                                            aria-label="Kuriér"
                                            id="courierID"
                                            v-model="courierID"
                                        >
                                            <option
                                                v-for="couriers in courier"
                                                :key="couriers.id"
                                                v-bind:value="couriers.id"
                                            >
                                                {{ couriers.courier_name }}
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
                    <button type="button" class="btn btn-success" @click="handleInsertService">Vytvoriť</button>
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
            courierID: "",
            serviceCode: "",
            serviceName: "",
            courier: [],
            services: [],
            error: null,
            counter:''
        }
    },

    created(){
        this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .post("./api/eshop-list")
                .then((response) => {
                    if (response.data) {
                        this.eshops = response.data;
                        // console.log(response.data);
                    } else {
                        console.log(error);
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
        });

         this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .post("./api/courier-list")
                .then((response) => {
                    if (response.data) {
                        this.courier = response.data;
                       console.log(response.data);
                    } else {
                        console.log(response.data);
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
        });
    },
    mounted: function () {
        this.$nextTick(function () {

            let local = this

            this.$axios.get('./sanctum/csrf-cookie').then(response => {
                this.$axios.post('./api/service-list')
                    .then(response => {

                        if (response.data) {
                            this.services = response.data;
                            this.counter=this.services.length;

                        } else {
                            Swal.fire({title: "Zoznam kuriérskych služieb", text: "Chyba!", icon: "warning"});
                        }

                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire({title: "Zoznam kuriérskych služieb", text: "Chyba:\n" + error, icon: "warning"});
                    });
            })

        })
    },

    methods: {

  /*      handleUpdate(id) { // TODO? → handleUpdate
            this.$axios.get(`./sanctum/csrf-cookie`).then((response) => {
                this.$axios
                    .post(`./api/service-update/${id}`)
                    .then((response) => {
                        if (response.data) {
                            this.services = response.data;
                            console.log(response.data);
                        } else {
                            console.log(error);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },*/
        handleInsertService(e) {
            e.preventDefault()
            if (this.serviceName.length > 0) {
                this.$axios.get('./sanctum/csrf-cookie').then(response => {
                    this.$axios.post('./api/service-insert', {
                        serviceCode: this.serviceCode,
                        serviceName: this.serviceName,
                        courierID: this.courierID,
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
                                        window.location.href = "./kurierske_sluzby"
                                    } else {
                                        window.location.href = "./kurierske_sluzby"
                                    }
                                });
                            } else {
                                this.error = response.data.message
                                Swal.fire({
                                    title: "Kurierske služby",
                                    text: response.data.message,
                                    icon: 'warning'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            Swal.fire({
                                title: "Kurierske služby",
                                text: error,
                                icon: 'warning'
                            });
                        });
                })
            }
        },
        handleDeleteService(id, name, courier) {
            Swal.fire({
                icon: "warning",
                title: `Naozaj chceš vymazať službu ${name} pokytovanú kuriérom ${courier}?`,
                showDenyButton: true,
                confirmButtonText: 'Vymazať',
                denyButtonText: `Nie, pomýlil som sa`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$axios.delete(`./api/service-delete/${id}`)
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
                                        this.services.splice(this.services.indexOf(id), 1);
                                    } else {
                                        this.services.splice(this.services.indexOf(id), 1);
                                    }
                                });
                            } else {
                                this.error = response.data.message
                                Swal.fire({
                                    title: "Kurierske služby",
                                    text: response.data.message,
                                    icon: 'warning'
                                });
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            Swal.fire({
                                title: "Kuriérske služby",
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
