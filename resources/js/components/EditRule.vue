<template>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruleModal">Upraviť pravidlo</h5>
                <router-link :to="{ name: 'pravidla' }" type="button" class="btn-close"></router-link>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form method="post" class="row g-2">
                        <div class="col-6">
                            <div class="rule-create-form border-right p-4">
                                <div class="col-12">
                                    <label for="RuleName" class="mb-2"
                                        >Názov pravidla</label
                                    >
                                    <input
                                        type="text"
                                        id="RuleName"
                                        name="RuleName"
                                        class="form-control"
                                        placeholder="Doručenie do okresu Bratislava..."
                                        v-model="RuleName"
                                    />
                                </div>
                                <div class="col-12">
                                    <label for="value" class="mb-2"
                                        >Porovnávacia hodnota</label
                                    >
                                    <input
                                        type="text"
                                        id="value"
                                        name="value"
                                        class="form-control"
                                        placeholder="Bratislava..."
                                        v-model="value"
                                    />
                                </div>
                                <div class="col-12">
                                    <label for="active" class="mb-2"
                                        >Je pravidlo aktívne?</label
                                    >
                                    <select
                                        v-model="active"
                                        class="form-select"
                                        aria-label="Je aktívne?"
                                        id="active"
                                    >
                                        <option value="1">Áno</option>
                                        <option value="0">Nie</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="priority" class="mb-2"
                                        >Priorita</label
                                    >
                                    <select
                                        id="priority"
                                        name="priority"
                                        class="form-select"
                                        v-model="priority"
                                    >
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rule-create-form border-right p-4">
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
                                <div class="col-12">
                                    <label for="predicateID" class="mb-2"
                                        >Predikát</label
                                    >
                                    <select
                                        class="form-select"
                                        aria-label="Predikát"
                                        id="predicateID"
                                        v-model="predicateID"
                                    >
                                        <option
                                            v-for="pre in predicate"
                                            :key="pre.id"
                                            v-bind:value="pre.id"
                                        >
                                            {{ pre.name_predicate }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="subjectID" class="mb-2"
                                        >Subjekt</label
                                    >
                                    <select
                                        class="form-select"
                                        aria-label="Predikát"
                                        id="subjectID"
                                        v-model="subjectID"
                                    >
                                        <option
                                            v-for="sub in subject"
                                            :key="sub.id"
                                            v-bind:value="sub.id"
                                        >
                                            {{ sub.name_subject }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="ruleCourierService" class="mb-2"
                                        >Kuriérska služba</label
                                    >
                                    <select
                                        class="form-select"
                                        aria-label="Kuriérska služba"
                                        id="ruleCourierService"
                                        v-model="serviceCode"
                                    >
                                        <option
                                            v-for="service in courier_service"
                                            :key="service.id"
                                            v-bind:value="service.id"
                                        >
                                            {{ service.service_code }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <router-link to="pravidla" type="button" class="btn btn-secondary">Zatvoriť</router-link>
                <button
                    type="button"
                    class="btn btn-success"
                    @click="handleUpdate(this.$route.params.id)"
                >
                    Upraviť
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";
export default {

    data() {
        return {
            RuleName: this.$route.params.rule_name,
            priority: this.$route.params.priority,
            value: this.$route.params.value,
            active: this.$route.params.active,
            serviceCode: this.$route.params.servicecodeid,
            eshopID: this.$route.params.eshop_id,
            subjectID: this.$route.params.subject_id,
            subjectName: this.$route.params.subject_name,
            predicateID: this.$route.params.predicate_id,
            predicateName: this.$route.params.predicate_name,
            rules: [],
            eshops: [],
            predicate: [],
            subject: [],
            courier_service: []
        };
    },
    created() {

          this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .get(`./api/${this.$route.params.id}`)
                .then((response) => {
                    if (response.data) {
                        this.rules = response.data;
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

        this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .post("./api/courier-service-list")
                .then((response) => {
                    if (response.data) {
                        this.courier_service = response.data;
                      //  console.log(response.data);
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
                .post("./api/subject-list")
                .then((response) => {
                    if (response.data) {
                        this.subject = response.data;
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
                .post("./api/predicate-list")
                .then((response) => {
                    if (response.data) {
                        this.predicate = response.data;
                 //    console.log(response.data);
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
                    .post(`./api/rule-update/${id}`,{
                     RuleName: this.RuleName,
                            priority: this.priority,
                            value: this.value,
                            active: this.active,
                            serviceCode: this.serviceCode,
                            eshopID: this.eshopID,
                            subjectID: this.subjectID,
                            predicateID: this.predicateID,
                    })
                    .then((response) => {
                        if (response.data) {
                            Swal.fire({
                                icon: "success",
                                    title: "Úspech!",
                                    text: "Úspešne upravé pravidlo",
                                    confirmButtonText: "OK",
                            }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "./pravidla";
                                    } else {
                                        window.location.href = "./pravidla";
                                    }
                                });
                            } else {
                                console.log(this.error);
                                this.error = "Neúspešne upravené pravidlo";
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
