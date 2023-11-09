<template>
    <div class="container-xl p-3">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h2>Zoznam pravidiel v databáze</h2>
                <p class="small">Počet pravidiel: {{ counter }}</p>
            </div>
            <div class="col-4">
                <button
                    type="button"
                    class="btn btn-success float-end"
                    data-bs-toggle="modal"
                    data-bs-target="#createRuleModal"
                >
                    Vytvoriť pravidlo
                </button>
            </div>
        </div>
        <div class="alert alert-dismissible alert-warning" shown="no">
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
            <h4 class="alert-heading">
                <!-- alert title -->
            </h4>
            <p class="mb-0">
                <!-- alert title -->
            </p>
        </div>
        <form class="d-inline">
            <h5>Vyber EShop pre ktorý chceš zobraziť pravidlá:</h5>
            <div class="form-group">
                <select
                    class="form-select"
                    aria-label="E-Shop"
                    id="eshopFilter"
                    v-model="eshopFilter"
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
            <button class="btn btn-secondary mt-2 mb-2" @click="handleFilterRules">
                Odoslať
            </button>
        </form>
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-8 col-sm-6">
                <table
                    class="table table-hover table-striped table-responsive-sm"
                >
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Názov pravidla</th>
                            <th class="float-right">Provnávacia hodnota</th>
                            <th class="float-right">Je aktívne?</th>
                            <th class="float-right">Priorita</th>
                            <th class="float-right">E-Shop</th>
                            <th class="float-right">Predikát</th>
                            <th class="float-right">Subjekt</th>
                            <th class="float-right">Kuriérska služba</th>
                            <th class="float-right">Akcia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rule in rules" :key="rule.id">
                            <th scope="row">{{ rule.rule_id }}</th>
                            <td>{{ rule.rule_name }}</td>
                            <td>{{ rule.value }}</td>
                            <td>{{ rule.active }}</td>
                            <td>{{ rule.priority }}</td>
                            <td>{{ rule.eshop_name }}</td>
                            <td>{{ rule.name_predicate }}</td>
                            <td>{{ rule.name_subject }}</td>
                            <td>{{ rule.service_code }}</td>
                            <td class="float-right">
                                <router-link
                                    :to="{
                                        name: 'editRule',
                                        params: {
                                            id: rule.rule_id,
                                            rule_name: rule.rule_name,
                                            value: rule.value,
                                            active: rule.active,
                                            priority: rule.priority,
                                            eshop_id: rule.eshop_id,
                                            subject_id: rule.subject_id,
                                            subject_name: rule.name_subject,
                                            predicate_id: rule.predicate_id,
                                            predicate_name: rule.predicate_name,
                                            servicecodeid:rule.service_id,
                                            service_code_name:rule.service_code,

                                        },
                                    }"
                                    type="button"
                                    class="btn btn-info me-1"
                                    >Upraviť</router-link
                                >
                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    @click="handleDeleteRule(rule.rule_id)"
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
    <div
        class="modal fade"
        id="createRuleModal"
        tabindex="-1"
        aria-labelledby="ruleModal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ruleModal">
                        Vytvoriť pravidlo
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
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
                                        <label
                                            for="ruleCourierService"
                                            class="mb-2"
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
                    <button
                        type="button"
                        class="btn btn-secondary me-1"
                        data-bs-dismiss="modal"
                    >
                        Zatvoriť
                    </button>
                    <button
                        type="button"
                        class="btn btn-success"
                        @click="handleInsert"
                    >
                        Vytvoriť
                    </button>
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
            RuleName: "",
            priority: "",
            value: "",
            active: "",
            serviceCode: "",
            eshopID: "",
            subjectID: "",
            predicateID: "",
            rules: [],
            eshops: [],
            predicate: [],
            subject: [],
            courier_service: [],
            counter: "",
            eshopFilter: "",
        };
    },
    created() {
        this.$axios.get("./sanctum/csrf-cookie").then((response) => {
            this.$axios
                .post("./api/rule-list")
                .then((response) => {
                    if (response.data) {
                        this.rules = response.data;
                        this.counter = this.rules.length;
                        // console.log(this.counter);
                    } else {
                        Swal.fire({
                            title: "Zoznam pravidiel",
                            text: "Chyba!",
                            icon: "warning"
                        });
                    }
                })
                .catch(function (error) {
                    console.error(error);
                    Swal.fire({
                        title: "Zoznam pravidiel",
                        text: "Chyba:\n" + error,
                        icon: "warning"
                    });
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
        handleFilterRules(e){
            e.preventDefault()
            if (this.eshopFilter.length !== 0) {
                this.$axios.get('./sanctum/csrf-cookie').then(response => {
                    this.$axios
                        .post(`./api/get-filtered-rules/${this.eshopFilter}`, {
                            eshopFilter: this.eshopFilter,
                        })
                        .then(response => {
                            if (response.data.success === false) {
                                this.error = response.data.message
                                Swal.fire({
                                    title: "Pravidlá",
                                    text: response.data.message,
                                    icon: 'warning'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Úspech!',
                                    text: "Pravidlá načítané! Klikni na OK pre zobrazenie.",
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.rules = [];
                                        this.rules = response.data;
                                    } else {
                                        this.rules = [];
                                        this.rules = response.data;
                                    }
                                });
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            Swal.fire({
                                title: "Pravidlá",
                                text: error,
                                icon: 'warning'
                            });
                        });
                })
            }
        },
        handleUpdate(id) {
            this.$axios.get(`./sanctum/csrf-cookie`).then((response) => {
                this.$axios
                    .post(`./api/rule-update/${id}`)
                    .then((response) => {
                        if (response.data) {
                            this.rules = response.data;
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
        handleInsert(e) {
            e.preventDefault();
            if (this.RuleName.length || value.length > 0) {
                this.$axios.get("./sanctum/csrf-cookie").then((response) => {
                    this.$axios
                        .post("./api/rule-insert", {
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
                            if (response.data.success) {
                                // console.log(response.data);
                                Swal.fire({
                                    icon: "success",
                                    title: "Úspech!",
                                    text: "Úspešne pridané pravidlo",
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
                                this.error = "Neúspešne pridané pravidlo";
                                Swal.fire({
                                    title: "Pravidlá",
                                    text: this.error,
                                    icon: "warning",
                                });
                            }
                        })
                        .catch(function (error) {
                            Swal.fire({
                                title: "Pravidlá",
                                text: error,
                                icon: "warning",
                            });
                        });
                });
            } else {
                Swal.fire({
                    title: "Pravidlá",
                    text: "Chýbajúce informácie",
                    icon: "warning",
                });
            }
        },
        handleDeleteRule(id) {
            this.$axios
                .delete(`./api/rule-delete/${id}`)
                .then((response) => {
                    // console.log(response.data);
                    if (response.data.success) {
                        this.counter--;
                        Swal.fire({
                            icon: "success",
                            title: "Úspech!",
                            text: response.data.message,
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.rules.splice(this.rules.indexOf(id), 1);
                            } else {
                                this.rules.splice(this.rules.indexOf(id), 1);
                            }
                        });
                    } else {
                        this.error = response.data.message;
                        Swal.fire({
                            title: "Pravidla",
                            text: response.data.message,
                            icon: "warning",
                        });
                    }
                })
                .catch(function (error) {
                    console.error(error);
                    Swal.fire({
                        title: "Pravidla",
                        text: error,
                        icon: "warning",
                    });
                });
        },
    },
};
</script>

<style></style>
