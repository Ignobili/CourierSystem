<template>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="serviceModal">Upraviť službu</h5>
                    <router-link to="kurierske_sluzby" type="button" class="btn-close"></router-link>
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
                    <router-link to="kurierske_sluzby" type="button" class="btn btn-secondary">Zatvoriť</router-link>
                    <button type="button" class="btn btn-success" @click="handleUpdate(this.$route.params.id)">Upraviť</button>
                </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";
export default {

    data() {
        return {
            serviceName: this.$route.params.service_name,
            serviceCode: this.$route.params.service_code,
            courierID: this.$route.params.courier_id,
            courier:[],
            services:[],
        };
    },
    created() {
    console.log(this.$route.params.courier_id);
    this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .get(`./api/${this.$route.params.id}`)
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
    this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .post("./api/courier-list")
                .then((response) => {
                    if (response.data) {
                        this.courier = response.data;
                     //  console.log(response.data);
                    } else {
                        console.log(error);
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
        });
    },
    methods: {
        handleUpdate(id) {
            this.$axios.get(`./sanctum/csrf-cookie`).then((response) => {
                this.$axios
                    .post(`./api/service-update/${id}`,{
                            serviceName: this.serviceName,
                            serviceCode: this.serviceCode,
                            courierID: this.courierID,
                    })
                    .then((response) => {
                        if (response.data) {
                            Swal.fire({
                                icon: "success",
                                    title: "Úspech!",
                                    text: "Úspešne upravená kuriérska služba",
                                    confirmButtonText: "OK",
                            }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "./kurierske_sluzby";
                                    } else {
                                        window.location.href = "./kurierske_sluzby";
                                    }
                                });
                            } else {
                                console.log(this.error);
                                this.error = "Neúspešne upravená kuriérska služba";
                                Swal.fire({
                                    title: "Pravidlá",
                                    text: this.error,
                                    icon: "warning",
                                });
                            }
                        })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },
    },
};
</script>

<style></style>
