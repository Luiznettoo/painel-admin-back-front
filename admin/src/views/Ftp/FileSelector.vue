<template>
    <section>
        <div class="file-icons">
            <draggable :value="value" @input="ordenar" group="images" @start="drag=true" @end="drag=false">
                <div v-for="(item, index) in value" class="file-wrapper" ref="item" :key="index" :data-item-id="item.id">
                    <div class="file"
                        :title="item.name">
                        <div class="detach" title="Remover" @click="detach(index)">
                            <i class="fa fa-minus-circle"></i>
                        </div>
                        <div class="item-icon"
                            :data-extension="getExtension(item.name)"
                            :data-file="!item.folder">
                            <img v-if="item.resources" class="img-fluid" :src="getThumbnail(item.resources)" />
                            <i v-else-if="!item.folder" class="fa fa-file"></i>
                            <i v-else class="fa fa-folder"></i>
                        </div>
                        <div class="item-name">{{ item.name }}</div>
                    </div>
                </div>
            </draggable>
        </div>
        <div v-if="max === -1 || value.length < max" class="new-item" @click="openModal">
            <i class="fa fa-plus"></i>
        </div>

        <div v-if="showModal" class="modal-wrapper">
            <div class="modal-overlay" @click="closeModal">
            </div>
            <div class="file-list-wrapper">
                <h3>Seletor de arquivos</h3>
                <div class="file-list-outer">
                    <ftp-list selector v-model="selection" :mime="mimeFilter"></ftp-list>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import FtpList from './FtpList';
    import {cloneObject} from "../../shared/utils";
    import draggable from 'vuedraggable';

    export default {
        name: "FileSelector",
        components: {
            draggable,
            FtpList
        },
        props: {
            min: {
                default: -1,
            },
            max: {
                default: -1,
            },
            mimeFilter: {
                type: String,
                default: '*/*'
            },
            value: {
                type: Array
            }
        },
        model: {
            prop: 'value',
            event: 'input'
        },
        data() {
            return {
                showModal: false,
                selection: [],
                currentSelection: []
            }
        },
        watch: {
            selection(filesBuffer) {
                if (!filesBuffer.length) {
                    return;
                }

                this.selection = [];

                let files = cloneObject(this.value).concat(filesBuffer);

                if (this.min !== -1 && files.length < this.min) {
                    $.alert({
                        title: 'Atenção!',
                        content: `Mínimo <b>${this.min}</b> arquivo${this.min > 1 ? 's' : ''}!`,
                        type: 'red',
                        closeIcon: true,
                        useBootstrap: true,
                        columnClass: 'col-md-8 col-md-offset-8 col-sm-12 col-sm-offset-6'
                    });

                    return;
                }
                else if (this.max !== -1 && files.length > this.max) {
                    $.alert({
                        title: 'Atenção!',
                        content: `Máximo <b>${this.max}</b> arquivo${this.min > 1 ? 's' : ''}!`,
                        type: 'red',
                        closeIcon: true,
                        useBootstrap: true,
                        columnClass: 'col-md-8 col-md-offset-8 col-sm-12 col-sm-offset-6'
                    });
                    return;
                }
                this.$emit('input', files);
                this.showModal = false;
            }
        },
        methods: {
            getThumbnail(resources) {
                for (const resource of resources) {
                    if (resource.width === 100 && resource.extension === 'webp') {
                        return resource.permalink;
                    }
                }
        
                return '';
            },
            closeModal() {
                this.showModal = false;
            },
            openModal() {
                this.showModal = true;
            },
            getExtension(filename) {
                let results = filename.match(/\.[a-z0-9]+$/i);

                if (results && filename.length - results[0].length > 0) {
                    return results[0].toUpperCase().substr(1, results[0].length);
                }

                return 'FILE';
            },
            detach(index) {
                let files = cloneObject(this.value);
                files.splice(index, 1);
                this.$emit('input', files);
            },
            ordenar(lista){
                this.$emit('input',lista);
            }
        },
        computed: {

        }
    }
</script>

<style scoped>
    .modal-wrapper {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;

        align-items: center;
        justify-content: center;

        z-index: 9999;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.31);

    }

    .file-icons {
        /*
        display: flex;
        */
    }

    .file-list-wrapper {
        position: relative;
        width: 86%;
        height: 90%;
        z-index: 1;
        padding: 60px 15px 15px 15px;
        background-color: #fff;
        border: 1px solid #989898;
        border-radius: 4px;
    }

    .file-list-outer {
        position: relative;
        height: 100%;
    }

    h3 {
        border-bottom: 1px solid #bbb;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 4px 8px;
    }

    .file-wrapper {
        display: inline-block;
        width: 100px;
        height: 100px;
        margin-right: 4px;
    }

    .file {
        position: absolute;
        display: flex;
        flex-direction: column;

        width: 100px;
        max-height: 100px;

        padding: 6px;
        border: 1px solid #0074de;
        border-radius: 2px;
        cursor: context-menu;
        background-color: rgba(0, 61, 191, 0.15);
    }

    .file:hover {
        background-color: rgba(0, 61, 191, 0.15);
    }

    .item-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        font-size: 60px;
        height: 60px;

        color: #1b4b72;
    }

    .item-name {
        overflow: hidden;
        font-size: 12px;
        text-align: center;
        max-height: 15px;
        line-height: 15px;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-wrap: break-word;
    }

    .item-icon[data-file] {
        color: #353535;
    }

    .item-icon {
        position: relative;
    }

    .item-icon[data-file="true"]::after {
        content: attr(data-extension);
        position: absolute;
        right: 21px;
        bottom: 5px;
        font-size: 12px;
        background-color: #b7b7b7;
        padding: 0 2px;
        border: 0 solid transparent;
        border-radius: 2px 0 0 2px;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-weight: bold;
    }

    .new-item {
        border: 1px solid #616161;
        width: 100px;
        height: 89px;
        border-radius: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 30px;
        background-color: #75757530;
        color: #008c00;
    }

    .new-item:hover {
        border-color: #74de00;
    }

    .new-item .fa {
        border: 2px dashed;
        border-radius: 50%;
        padding: 10px;
    }

    .img-fluid {
        align-self: center;
        flex: 0 0 auto;
        max-height: 100%;
    }

    .detach {
        position: absolute;
        right: 4px;
        top: 0;
        color: #7d0000;
        font-size: 14px;
        z-index: 1;
    }

    .detach:hover {
        color: #bf0000;
    }
</style>
