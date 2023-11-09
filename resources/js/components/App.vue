<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <router-link :to="{ name: 'home' }" class="navbar-brand">UKFWebKurier.sk</router-link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item" v-if="isLoggedIn">
                            <router-link :to="{ name: 'home' }" class="nav-link">Domov</router-link>
                        </li>
                        <li class="nav-item" v-if="isLoggedIn">
                            <router-link :to="{ name: 'eshopy' }" class="nav-link">E-Shopy</router-link>
                        </li>
                        <li class="nav-item" v-if="isLoggedIn">
                            <router-link :to="{ name: 'pravidla' }" class="nav-link">Pravidlá</router-link>
                        </li>
                        <li class="nav-item" v-if="isLoggedIn">
                            <router-link :to="{ name: 'kurieri' }" class="nav-link">Kuriéri</router-link>
                        </li>
                        <li class="nav-item" v-if="isLoggedIn">
                            <router-link :to="{ name: 'kurierske_sluzby' }" class="nav-link">Kuriérske služby
                            </router-link>
                        </li>
                        <li class="nav-item dropdown" v-if="isLoggedIn">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                               aria-haspopup="true" aria-expanded="false">Používatelia</a>
                            <div class="dropdown-menu">
                                <router-link :to="{ name: 'create_user' }" class="dropdown-item">Vytvoriť používateľa
                                </router-link>
                                <router-link :to="{ name: 'users_overview' }" class="dropdown-item">Prehľad
                                    používateľov
                                </router-link>
                            </div>
                        </li>
                        <li class="nav-item" v-if="isLoggedIn">
                            <a class="nav-link text-danger" style="cursor: pointer" @click="logout">Odhlásiť sa</a>
                        </li>
                        <li class="nav-item" v-else>
                            <router-link :to="{ name: 'login' }" class="nav-link">Prihlásiť sa</router-link>
                        </li>
                    </ul>
                </div>
                <a v-if="isLoggedIn" style="color: rgb(255, 255, 255);">Vitaj {{ name }}</a>
            </div>
        </nav>
    </header>
    <main class="flex-shrink-0">
        <div class="container-fluid">
            <router-view></router-view>
        </div>
    </main>
    <footer class="footer mt-auto text-center text-lg-start border-top border-primary">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Technológie</h5>
                    <p>
                        Vytvorené pomocou PHP frameworku Laravel a front-end frameworku Vue.js
                    </p>
                </div>
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0 float-end">
                    <h5 class="text-uppercase">Autori</h5>
                    <p>
                        Systém vytvorili
                    </p>
                    <ul>
                        <li>
                            Bc. Richard Gahér
                        </li>
                        <li>
                            Bc. Jakub Hodoši
                        </li>
                        <li>
                            Bc. Peter Iždinský
                        </li>
                        <li>
                            Bc. Andrej Bíro
                        </li>
                        <li>
                            Bc. Martin Paučo
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center p-2">
            <button id="themeSwitch" class="btn btn-primary">🌙</button>
        </div>
        <div class="text-center p-3 bg-primary text-white">
            2021 ScrumTeamOne | UKF Web Kuriér | UKF
        </div>
    </footer>
</template>

<script>
export default {
    name: "App",
    data() {
        return {
            name: "",
            isLoggedIn: false,
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
        }
        if (window.Laravel.user) {
            this.name = window.Laravel.user.name
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedIn) {
            window.location.href = "./";
        }
        next();
    },
    methods: {
        logout(e) {
            console.log('ss')
            e.preventDefault()
            this.$axios.get('./sanctum/csrf-cookie').then(response => {
                this.$axios.post('./api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "./login"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";SameSite=Strict; Secure;" + "path=/";
}

import $ from 'jquery';

$(document).ready(function () {
    $('#themeSwitch').click(function () {
        let themeSelected = $('#themeLink').attr('class');
        if (themeSelected === 'light') {
            setCookie("UserThemeUKFWebKurier", "themeIsDark", 90);
            $('link[id="themeLink"]').attr('href', './css/dark_mode.css').attr('class', 'dark');
            $('#themeSwitch').html('&#x1F31E;');
        }
        if (themeSelected === 'dark') {
            setCookie("UserThemeUKFWebKurier", "", -1);
            $('link[id="themeLink"]').attr('href', './css/light_mode.css').attr('class', 'light');
            $('#themeSwitch').html('🌙');
        }
    });
});
$(document).ready(function () {
    if (document.cookie.indexOf('UserThemeUKFWebKurier') > -1) {
        $('#themeSwitch').html('&#x1F31E;');
    }
});
</script>
<style>

</style>
