<template>
    <div class="row justify-content-center">
        <div class="col-4 text-center"><h1>Welcome</h1>
            <h2>to</h2>
            <h1>Food@Home</h1>
        </div>
    </div>
</template>

<script>
export default {
    auth: {
        required: false
    },
    name: "index",
}
</script>

<style scoped>
.row {
    background-image: url('../../../public/img/jakub-kapusnak-sDbj1dFlFPU-unsplash.jpg');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    position: fixed;
    width: 100%;
    height: 100%;
    left: 0;
    margin: -5px 0 0;
}

.col-4 {
    padding-top: 15%;
}

@keyframes appear {
    0% {
        opacity: 0;
    }
}
</style>
