<template>
    <div class="container-xl p-3">
        <h2>Administračné prostredie</h2>
        <p class="small time text-capitalize">Deň 0.00.0000 00:00:00</p>
        <div class="alert alert-dismissible alert-warning" shown="no">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <h4 class="alert-heading"><!-- alert title --></h4>
            <p class="mb-0"><!-- alert title --></p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-5 col-sm-6 p-2">
                <div class="card border-primary mb-3">
                    <div class="card-header">Databáza</div>
                    <div class="card-body">
                        <h4 class="card-title">Štatistiky databázy</h4>
                        <p class="card-text">Počet tabuliek: 6</p>
                        <p class="card-text">Veľkosť databázy: 2 MB</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-6 p-2">
                <div class="card border-primary mb-3">
                    <div class="card-header">Server</div>
                    <div class="card-body">
                        <h4 class="card-title">Využitie servera</h4>
                        <p class="card-text">% CPU: 2%</p>
                        <p class="card-text">Zaplnenie úložiska: 3%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Home",
        data() {

        },
        methods: {
            //
        }
    }

    import $ from 'jquery';

    $(document).ready(function() {
        clockUpdate();
        setInterval(clockUpdate, 1000);
    })
    function clockUpdate() {
        let date = new Date();
        function addZero(x) {
            if (x < 10) {
                return x = '0' + x;
            } else {
                return x;
            }
        }
        let dayName = date.toLocaleDateString("sk-SK", { weekday: 'long' });
        let year = date.getFullYear();
        let day = date.getDate();
        let month = date.getMonth()+1;
        let h = addZero(date.getHours());
        let m = addZero(date.getMinutes());
        let s = addZero(date.getSeconds());
        $('.time').text(dayName + ' ' + day + '.' + month + '.' + year + ' ' + h + ':' + m + ':' + s);
    }

</script>

<style>

</style>
