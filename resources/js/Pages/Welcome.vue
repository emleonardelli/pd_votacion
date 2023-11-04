<template>
    <Head title="Elecciones 2023" />
    <Toast />
    <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 sm:pt-0">
        <div class="relative flex items-top justify-center">
            <div class="max-w-6xl sm:px-6 lg:px-8">
                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="ml-1 text-lg leading-7 font-semibold">
                                <span class="underline text-gray-900 dark:text-white">CARGA DE VOTOS PRESIDENTE</span>
                            </div>
                        </div>
                        <br>
                        <div class="p-fluid">
                            <div class="p-field">
                                <label
                                    style="width: 50%; float: left"
                                    for="mesa_presidente">Mesa N&deg;</label>
                                <InputText
                                    style="width: 50%; float: left;margin-bottom: 10px"
                                    id="mesa_presidente" type="number" />
                            </div>
                            <div class="p-field">
                                <label
                                    style="width: 50%; float: left"
                                    for="total_presidente">Total Votantes Empadronados</label>
                                <InputText
                                    style="width: 50%; float: left;margin-bottom: 10px;"
                                    id="total_presidente" type="number" value="330"/>
                            </div>
                        </div>
                        <div class="p-fluid">
                            <div
                                v-for="candidate in candidatePresidentes"
                                :key="candidate"
                                class="p-field"
                            >
                                <label
                                    style="width: 70%; float: left"
                                    :for="`candidato_presidente_${candidate.id}`"
                                    v-text="candidate.titulo"
                                ></label>
                                <InputText
                                    style="width: 30%; float: left; margin-bottom: 10px"
                                    :id="`candidato_presidente_${candidate.id}`"
                                    type="number"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="p-fluid">
                            <div class="p-field">
                                <div class="flex items-center">
                                    <div class="ml-1 text-lg leading-7 font-semibold">
                                        <span class="underline text-gray-900 dark:text-white">CARGA EL CERTIFICADO DEL FISCAL</span>
                                    </div>
                                </div>
                                <p>Chequeá que la imagen se vea nítida y completa antes de subirla.</p>
                                <br>
                                <InputText
                                    style="width: 100%; float: left; margin-bottom: 10px"
                                    type="file"
                                    ref="file"
                                    @change="uploadFile"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                    <div class="p-6">
                        <div class="p-fluid">
                            <div class="p-field">
                                <Button class="p-button-lg" label="Guardar" @click="save()"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-700 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }
</style>

<script>
    import { ref, defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import Button from 'primevue/button';
    import InputText from 'primevue/inputtext';
    import Toast from 'primevue/toast';
    export default defineComponent({
        data() {
            return {
                candidatePresidentes: [],
                candidateDiputados: [],
                certificado_id: null,
            }
        },
        mounted() {
            this.getCandidates();
        },
        methods: {
            uploadFile(event) {
                //this.file = event.target.files[0];
                let formData = new FormData();
                formData.append('file', event.target.files[0]);
                axios.post('/api/upload-file', formData)
                .then(response => {
                    this.certificado_id = response.data.certificado_id;
                    this.$toast.add({
                        severity:'success',
                        summary: 'Guardado exitoso',
                        detail: 'Certificado guardado exitosamente',
                        life: 2000
                    });
                })
                .catch(error => {
                    this.$toast.add({
                        severity:'error',
                        summary: 'Error en carga de archivo!',
                        detail: error,
                        life: 3000
                    });
                });
            },
            save() {
                let model = {
                    mesa_presidente: document.getElementById('mesa_presidente').value,
                    total_presidente: document.getElementById('total_presidente').value,
                };
                this.candidatePresidentes.map((candidate) => {
                    model[`candidato_presidente_${candidate.id}`] = document.getElementById(`candidato_presidente_${candidate.id}`).value;
                });
                model['certificado_id']=this.certificado_id;
                axios.post('/api/saveCandidates', model).then((res) => {
                    if (res.data.status == 406) {
                        this.$toast.add({
                            severity:'error',
                            summary: 'Error!',
                            detail:res.data.error,
                            life: 3000
                        });
                    }else{
                        this.$toast.add({
                            severity:'success',
                            summary: 'Operación Exitosa',
                            detail: 'Formulario guardado',
                            life: 1000
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                });
            },
            getCandidates() {
                return axios.get('/api/getCandidates').then((data) => {
                    this.candidatePresidentes = data.data.filter(item => item.eleccion === 'presidente');
                });
            },
        },
        components: {
            Head,
            Link,
            Button,
            InputText,
            Toast,
        },
    })
</script>
