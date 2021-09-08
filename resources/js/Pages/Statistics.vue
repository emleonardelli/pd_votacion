<template>
    <app-layout title="Elecciones 2021">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Estadisticas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div style="width: 20%; float: left; margin-bottom: 10px">
                        <Button @click="filtrar('General')"      label="General"/><br><br>
                        <Button @click="filtrar('Capital')"      label="Neuquen Capital"/><br><br>
                        <Button @click="filtrar('Norte')"        label="Zona Norte"/><br><br>
                        <Button @click="filtrar('Este')"         label="Zona Este"/><br><br>
                        <Button @click="filtrar('Confluencia')"  label="Zona Confluencia"/><br><br>
                        <Button @click="filtrar('Sur')"          label="Zona Sur"/>
                    </div>
                    <div style="width: 80%; float: left; margin-bottom: 10px">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight" v-text="filter"></h2>
                        <Chart
                            :type="type"
                            :data="chartData"/>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Chart from 'primevue/chart';
    import Button from 'primevue/button';

    export default defineComponent({
        components: {
            AppLayout,
            Chart,
            Button,
        },
        data() {
            return {
                type: 'bar', //bar, pie, doughnut
                filter: 'General',
                chartData: {
                    labels: [],
                    datasets: [
                        {
                            data: [],
                            backgroundColor: [],
                        }
                    ]
                },
            }
        },
        mounted() {
            this.getVotes(this.filter);
        },
        methods: {
            getVotes() {
                return axios.get(`/api/getVotes/${this.filter}`).then((data) => {
                    let labels = [];
                    let dataset = {
                        data: [],
                        backgroundColor: [],
                    }
                    data.data.map(candidato => {
                        labels.push(candidato.nombre);
                        dataset.data.push(candidato.votos);
                        dataset.backgroundColor.push(candidato.color);
                    });
                    const chartData = {
                        labels: labels,
                        datasets: [dataset]
                    }
                    this.chartData = chartData;
                });
            },
            filtrar(filter) {
                this.filter=filter;
                this.getVotes();
            },
        },
    })
</script>
