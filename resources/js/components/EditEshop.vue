<template>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModal">Upraviť službu</h5>
                <router-link :to="{ name: 'eshopy' }" type="button" class="btn-close"></router-link>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="service-create-form border-right p-4">
                                <form method="post" class="row g-3">
                                    <div class="col-12">
                                        <label for="eshopID" class="mb-2">ID Eshopu</label>
                                        <input
                                            type="text"
                                            id="eshopID"
                                            name="eshopID"
                                            class="form-control"
                                            v-model="eshopID"
                                            disabled
                                        />
                                    </div>
                                    <div class="col-12">
                                        <label for="eshopName" class="mb-2">Názov EShopu</label>
                                        <input
                                            type="text"
                                            id="eshopName"
                                            name="eshopName"
                                            class="form-control"
                                            placeholder="Alza.sk, Datart, Mall.sk..."
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
                <router-link to="eshopy" type="button" class="btn btn-secondary">Zatvoriť</router-link>
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
            eshopName: this.$route.params.eshop_name,
            eshopID: this.$route.params.id,
            eshopy:[],
        };
    },
    created() {
        console.log(this.$route.params.id);
        this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .get(`./api/${this.$route.params.id}`)
                .then((response) => {
                    if (response.data) {
                        this.eshopy = response.data;
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
    methods: {
        handleUpdate(id) {
            this.$axios.get(`./sanctum/csrf-cookie`).then((response) => {
                this.$axios
                    .post(`./api/eshop-update/${id}`,{
                        eshopID: this.eshopID,
                        eshopName: this.eshopName,
                    })
                    .then((response) => {
                        if (response.data) {
                            Swal.fire({
                                icon: "success",
                                title: "Úspech!",
                                text: response.data.message,
                                confirmButtonText: "OK",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "./eshopy";
                                } else {
                                    window.location.href = "./eshopy";
                                }
                            });
                        } else {
                            console.log(this.error);
                            this.error = "Neúspešne upravený EShop!";
                            Swal.fire({
                                title: "EShopy",
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
