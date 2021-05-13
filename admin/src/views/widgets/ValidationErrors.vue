<template>
    <div v-if="validationErrors">
        <ul class="alert alert-danger list-unstyled">
            <li v-for="(value) in validationErrors">{{ value }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        data(){
            return {

            }
        },
        props: ['errors'],
        computed: {
            validationErrors(){
                let errors = Object.values(this.errors);
                errors = errors.flat();
                return errors;
            }
        }
    }
</script>