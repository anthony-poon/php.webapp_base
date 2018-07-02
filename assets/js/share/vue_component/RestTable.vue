<template>
    <table class="table table-hover">
        <thead>
        <tr>
            <th v-for="k in tCol"
                :class=k.class
                :key=k.key
            >{{ k.header }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in tBody" v-on:click="onRowClick($event, row)">
            <td v-for="k in tCol">{{ row[k.key] }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            "tBody",
            "tCol",
            "onRowClick"
        ]
    }
</script>

<style scoped>

</style>