<template>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courierModal">Upraviť kuriéra</h5>
                <router-link :to="{ name: 'kurieri' }" type="button" class="btn-close"></router-link>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <div class="courier-create-form border-right p-4">
                                <form method="post" class="row g-2">
                                    <div class="col-12">
                                        <label for="courierName" class="mb-2"
                                        >Názov</label
                                        >
                                        <input
                                            type="text"
                                            id="courierName"
                                            name="courierName"
                                            class="form-control"
                                            placeholder="UPS,DPD..."
                                            v-model="courierName"
                                        />
                                    </div>
                                    <div class="col-12">
                                        <label for="courierCode" class="mb-2"
                                        >Kód</label
                                        >
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
                                        <label for="eshopID" class="mb-2"
                                        >E-Shop</label
                                        >
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
                <router-link to="kurieri" type="button" class="btn btn-secondary">Zatvoriť</router-link>
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
                courierName: this.$route.params.courier_name,
                courierCode: this.$route.params.courier_code,
                eshopID: this.$route.params.eshop_id,
                eshops: [],
                couriers: [],
            };
        },
        created() {

            this.$axios.get("./sanctum/csrf-cookie").then((response) => {
                this.$axios
                    .get(`./api/${this.$route.params.id}`)
                    .then((response) => {
                        if (response.data) {
                            this.couriers = response.data;
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
        },
        methods: {
            handleUpdate(id) {
                this.$axios.get(`./sanctum/csrf-cookie`).then((response) => {
                    this.$axios
                        .post(`./api/courier-update/${id}`,{
                            courierName: this.courierName,
                            courierCode: this.courierCode,
                            eshopID:   this.eshopID,
                        })
                        .then((response) => {
                            if (response.data) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Úspech!",
                                    text: "Úspešne upravený kuriér",
                                    confirmButtonText: "OK",
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "./kurieri";
                                    } else {
                                        window.location.href = "./kurieri";
                                    }
                                });
                            } else {
                                console.log(this.error);
                                this.error = "Neúspešne upravený kuriér";
                                Swal.fire({
                                    title: "Kurieri",
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

<style scoped>

</style>
