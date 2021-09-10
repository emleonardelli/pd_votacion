<template>
    <app-layout title="Elecciones 2021">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Resultado conteo estadístico
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div style="width: 20%; float: left; margin-bottom: 10px">
                        <Button @click="filtrar('Provincial')"      label="Provincial"/><br><br>
                        <Button @click="filtrar('Capital')"      label="Neuquen Capital"/><br><br>
                        <Button @click="filtrar('Norte')"        label="Zona Norte"/><br><br>
                        <Button @click="filtrar('Oeste')"        label="Zona Oeste"/><br><br>
                        <Button @click="filtrar('Confluencia')"  label="Zona Confluencia"/><br><br>
                        <Button @click="filtrar('Sur')"          label="Zona Sur"/><br><br><br>
                        <Button @click="exportar()"              label="Exportar a XLS" class="p-button-warning"/>
                    </div>
                    <div style="width: 80%; float: left; margin-bottom: 10px">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight" v-text="filter"></h2>
                        <Chart
                            :type="type"
                            :data="chartData"
                            :options="options"/>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td>
                                    <div style="padding: 15px;border: 1px solid black; border-radius: 10px">
                                        Porcentaje de asistencia<br><span v-html="asistencia" style="font-weight: bold"></span>
                                    </div>
                                </td><td>
                                    <div style="padding: 15px;border: 1px solid black; border-radius: 10px">
                                        Blancos y Nulos<br><span v-html="nulos" style="font-weight: bold"></span>
                                    </div>
                                </td>
                                <td>
                                    <div style="padding: 15px;border: 1px solid black; border-radius: 10px">
                                        Mesas computadas<br><span v-html="mesas" style="font-weight: bold"></span>
                                    </div>
                                </td>
                            </tr>
                        </table>
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
                filter: 'Provincial',
                chartData: {
                    labels: [],
                    datasets: []
                },
                options: {
                    scales: {
                        y: {
                            title: {
                                display: true, 
                                text: 'Porcentaje',
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return value+' %';
                                }
                            }
                        },
                    }
                },
                nulos: 0,
                mesas: 0,
                asistencia: 0,
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
                        label: 'Candidatos',
                        data: [],
                        backgroundColor: [],
                    }
                    data.data.grafico.map(candidato => {
                        labels.push(candidato.nombre);
                        dataset.data.push(candidato.votos);
                        dataset.backgroundColor.push(candidato.color);
                    });
                    const chartData = {
                        labels: labels,
                        datasets: [dataset]
                    }
                    this.chartData = chartData;
                    this.nulos = data.data.votos_nulos;
                    this.mesas = data.data.mesas_computadas;
                    this.asistencia = data.data.asistencia;
                });
            },
            filtrar(filter) {
                this.filter=filter;
                this.getVotes();
            },
            exportar() {
                window.open('/api/exportar', '_blank');
            }
        },
    })
</script>
