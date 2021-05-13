<template>
    <article ref="dropzone" :style="articleStyle">
        <div class="heading">
            <div class="navigation">
                <div class="buttons">
                    <div :class="{'enabled': !!listview, 'button': true}" title="Visualizar ícones grandes"
                         @click="listview = false">
                        <i class="fa fa-th"></i>
                    </div>
                    <div :class="{'enabled': !listview, 'button': true}" title="Visualisar em lista"
                         @click="listview = true">
                        <i class="fa fa-list-ul"></i>
                    </div>
                    <div class="separator">&nbsp;</div>
                    <div class="enabled upload button"
                         title="Carregar arquivos"
                         @click="showUploadbox">
                        <i class="fa fa-folder"></i>
                    </div>
                    <div class="enabled new-folder button" title="Nova pasta"
                         @click="contextMenuChose(null, 'make-folder')">
                        <i class="fa fa-folder"></i>
                    </div>
                    <div :class="{'cut button': true, 'enabled': selectedItems.length}" title="Recortar"
                         @click="contextMenuChose(null, 'cut')">
                        <i class="fa fa-folder"></i>
                    </div>
                    <div :class="{'copy button': true, 'enabled': selectedItems.length}" title="Copiar"
                         @click="contextMenuChose(null, 'copy')">
                        <i class="fa fa-folder"></i>
                    </div>
                    <div :class="{'enabled': clipboard.items.length, 'paste  button': true}"
                         :title="'Colar' + (clipboard.items.length > 0 ? ` (${clipboard.items.length} ite${clipboard.items.length > 1 ? 'ns' : 'm'})` : '')"
                         @click="contextMenuChose(null, 'paste')">
                        <i class="fa fa-folder"></i>
                    </div>
                    <div :class="{'delete button': true, 'enabled': selectedItems.length}"
                         :title="'Deletar' + (selectedItems.length > 0 ? ` (${selectedItems.length} ite${selectedItems.length > 1 ? 'ns' : 'm'})` : '')"
                         @click="contextMenuChose(null, 'delete')">
                        <i class="fa fa-times"></i>
                    </div>
                    <div class="separator">&nbsp;</div>
                    <div :class="{'enabled': currentFolder !== 1, 'button': true}" @click="goRoot" title="Raiz">
                        <i class="fa fa-home"></i>
                    </div>
                    <div class="enabled button" @click="getListing" title="Atualizar">
                        <i class="fa fa-redo"></i>
                    </div>
                    <div :class="{'enabled': currentPath.length > 1, 'button': true}" @click="goBack" title="Voltar">
                        <i class="fa fa-arrow-circle-left"></i>
                    </div>
                    <div :class="{'enabled': forwardPath.length, 'button': true}" @click="goForward" title="Avançar">
                        <i class="fa fa-arrow-circle-right"></i>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <input class="form-control" type="text" :value="currentPathName.join(' > ')"
                           onkeydown="return false;" disabled/>
                </div>
            </div>
        </div>
        <div v-if="listview" class="file-list-heading">
            <div class="name">Nome</div>
            <div class="date">Adicionado em</div>
            <div class="type">Tipo</div>
            <div class="size">Tamanho</div>
        </div>
        <div class="overflow-wrapper" ref="fileList">
            <div class="overflow-content">
                <div v-click-outside="clearSelection"
                     @click.prevent="clearSelection"
                     @contextmenu.prevent="clearSelection($event, true)"
                     :class="{'files-wrapper overflow-content': true, 'list-details': listview}"
                     :style="fileListStyle">
                    <div v-for="(item,index) in items" class="file-wrapper" ref="item" :key="item.id" :data-item-id="item.id">
                        <div :class="{file: true, selected: selectedItems.includes(item.id), last: lastClickedItem === item.id, editing: (editingNameID === index)}"
                             @click.prevent.stop="itemClick($event, item.id)"
                             @contextmenu.prevent.stop="itemClick($event, item.id, true)"
                             :title="(!listview && !$refs.contextMenu.opened) ? item.name : ''">
                            <div :class="{ 'item-icon': true, 'cut-clipboard': (clipboard.mode === 'cut' && clipboard.items.includes(item.id))}"
                                 :data-extension="getExtension(item)"
                                 :data-file="!item.folder">
                                <img v-if="(item.resources.length || item.mime === 'image/svg+xml') && !listview" class="img-fluid"
                                     :src="getThumbnail(item)"/>
                                <i v-else-if="!item.folder" class="fa fa-file"></i>
                                <i v-else class="fa fa-folder"></i>
                            </div>
                            <input v-if="editingNameID === index"
                                   ref="nameInput"
                                   type="text"
                                   class="item-name box-input light"
                                   v-model="items[index].name"
                                   @blur="editingNameID = -1"/>
                            <div v-else class="item-name">{{ item.name }}</div>
                            <div v-if="listview" class="date-created">{{ parseDate(item.created_at) }}</div>
                            <div v-if="listview" class="mime">{{ !item.folder ? item.mime : 'Pasta de arquivos' }}</div>
                            <div v-if="listview" class="size">{{ parseSize(item) }}</div>
                        </div>
                    </div>
                    
                    <context-menu ref="contextMenu"
                                  :options="contextMenuOptions"
                                  @chose="contextMenuChose">
                    </context-menu>
                </div>
            </div>
        </div>
        <div v-if="showDropZone" class="dropzone-overlay">
            <div class="wrapper">
                Solte arquivos aqui para fazer upload...
            </div>
        </div>
        
        <form hidden><input type="file" multiple ref="filesInput" @change="onFileChange"></form>
        
        <div v-if="uploadProgress !== -1" class="upload-progress">
            <div :class="{'text': true, 'done': uploadProgress === 100}">
                Enviando arquivos... {{ uploadProgress }}%
            </div>
        </div>
        <div v-else-if="processingUpload" class="upload-progress">
            <div :class="{'text': true}">
                Processando imagem...
            </div>
        </div>
    </article>
</template>

<script>
    import ContextMenu                from './ContextMenu';
    import vClickOutside              from 'vue-click-outside';
    import {cloneObject, findInArray} from '../../../src/shared/utils';
    
    export default {
        name      : "FtpList",
        props     : {
            selector : {
                type   : Boolean,
                default: false
            },
            selection: {
                type    : Array,
                required: false
            },
            mime     : {
                type   : String,
                default: '*/*'
            }
        },
        model     : {
            prop : 'selection',
            event: 'select'
        },
        components: {
            ContextMenu
        },
        directives: {
            clickOutside: vClickOutside
        },
        data() {
            return {
                articleStyle           : {
                    height: '0'
                },
                fileListStyle          : {
                    height: '0'
                },
                listview               : false,
                currentPathName        : ['root'],
                currentPath            : [1],
                currentFolder          : 1,
                forwardPathName        : [],
                forwardPath            : [],
                selectedItems          : [],
                holdingCtrl            : false,
                holdingShift           : false,
                lastClickedItem        : -1,
                lastClickedTime        : -1,
                showDropZone           : false,
                formData               : null,
                uploadProgress         : -1,
                processingUpload       : false,
                dragTimer              : null,
                clipboard              : {
                    mode        : null,
                    items       : [],
                    itemsDetails: []
                },
                editingNameID          : -1,
                editingName            : '',
                contextMenuOptions     : [],
                contextMenuOptionsTypes: {
                    file  : [
                        {
                            name    : 'Selecionar',
                            slug    : 'select',
                            default : this.selector,
                            disabled: !this.selector
                        },
                        {
                            name    : 'Abrir',
                            slug    : 'open',
                            default : !this.selector,
                            disabled: true
                        },
                        {
                            name    : 'Download',
                            slug    : 'download',
                            default : true,
                            disabled: false
                        },
                        {
                            name: 'Copiar link',
                            slug: 'copy-link',
                        },
                        {
                            name: 'Recortar',
                            slug: 'cut',
                        },
                        {
                            name: 'Copiar',
                            slug: 'copy',
                        },
                        {
                            name: 'Excluir',
                            slug: 'delete',
                        },
                        {
                            name: 'Renomear',
                            slug: 'rename',
                        },
                        {
                            name: 'Metadata',
                            slug: 'meta',
                        }
                    ],
                    folder: [
                        {
                            name   : 'Abrir',
                            slug   : 'open',
                            default: true
                        },
                        {
                            name: 'Recortar',
                            slug: 'cut',
                        },
                        {
                            name: 'Copiar',
                            slug: 'copy',
                        },
                        {
                            name    : 'Colar',
                            slug    : 'paste',
                            disabled: true
                        },
                        {
                            name: 'Excluir',
                            slug: 'delete',
                        },
                        {
                            name: 'Renomear',
                            slug: 'rename',
                        }
                    ],
                    both  : [
                        {
                            name: 'Recortar',
                            slug: 'cut',
                        },
                        {
                            name: 'Copiar',
                            slug: 'copy',
                        },
                        {
                            name: 'Excluir',
                            slug: 'delete',
                        },
                        {
                            name: 'Renomear',
                            slug: 'rename',
                        }
                    ],
                    blank : [
                        {
                            name: 'Atualizar',
                            slug: 'refresh',
                        },
                        {
                            name: 'Criar pasta',
                            slug: 'make-folder',
                        },
                        {
                            name: 'Colar',
                            slug: 'paste',
                        }
                    ]
                },
                items                  : [],
                blobPreviews           : {},
                
                mimePattern: null,
                
                lastChar  : null,
                charOffset: -1,
            }
        },
        mounted() {
            this.getListing();
            this.fitWindow();
            
            window.addEventListener('resize', this.fitWindow);
            window.addEventListener('keydown', this.detectKeys);
            window.addEventListener('keydown', this.detectShortcuts);
            window.addEventListener('keyup', this.detectKeys);
            document.addEventListener('dragover', this.dragOver, true);
            document.addEventListener('dragleave', this.dragLeave, true);
            document.addEventListener('drop', this.drop, true);
        },
        beforeDestroy() {
            window.removeEventListener('resize', this.fitWindow);
            window.removeEventListener('keydown', this.detectKeys);
            window.removeEventListener('keydown', this.detectShortcuts);
            window.removeEventListener('keyup', this.detectKeys);
            document.removeEventListener('dragover', this.dragOver, true);
            document.removeEventListener('dragleave', this.dragLeave, true);
            document.removeEventListener('drop', this.drop, true);
        },
        watch     : {
            editingNameID(index, lastIndex) {
                if (index === -1) {
                    if (this.editingName === this.items[lastIndex].name) {
                        return;
                    }
                    
                    for (let item of this.items) {
                        if (this.items[lastIndex].id !== item.id && item.name === this.items[lastIndex].name && item.folder === this.items[lastIndex].folder) {
                            this.selectedItems = [this.items[lastIndex].id];
                            this.editingNameID = lastIndex;
                            
                            return;
                        }
                    }
                    
                    this.selectedItems = [this.items[lastIndex].id];
                    
                    this.setName(lastIndex);
                } else {
                    this.editingName = this.items[index].name;
                    
                    this.$nextTick(function () {
                        let end = this.items[index].name.length;
                        
                        if (!this.items[index].folder) {
                            let results = this.items[index].name.match(/\.[a-z0-9]+$/i);
                            
                            if (results && this.items[index].name.length - results[0].length > 0) {
                                end = this.items[index].name.length - results[0].length;
                            }
                        }
                        
                        this.$refs.nameInput[0].focus();
                        this.$refs.nameInput[0].setSelectionRange(0, end);
                        this.$refs.nameInput[0].addEventListener('keypress', (e) => {
                            if (e.keyCode === 13) {
                                this.editingNameID = -1;
                            }
                        });
                    });
                }
            },
            'clipboard.items': function (data) {
                this.contextMenuOptionsTypes.folder[3].disabled = !data.length;
            },
            formData() {
                _axios.post('files/upload', this.formData, {
                    headers         : {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (e) => {
                        this.uploadProgress = parseInt(e.loaded / e.total * 100);
                        
                        if (e.loaded === e.total) {
                            setTimeout(() => {
                                this.uploadProgress = -1;
                                
                                this.processingUpload = true;
                            }, 2000);
                        }
                    }
                })
                      .then((reponse) => {
                          this.processingUpload = false;
                    
                          for (let item of reponse.data) {
                              this.items.push(item);
                          }
                    
                          this.sort();
                      });
            },
            mime             : {
                handler  : function (v) {
                    if (v !== '*/*') {
                        let patternBuffer = v.replace('/', '\\/').replace('*', '[^\\/]+');
                        
                        this.mimePattern = new RegExp(patternBuffer, 'i');
                    } else {
                        this.mimePattern = null;
                    }
                },
                immediate: true
            }
        },
        methods   : {
            getThumbnail(image) {
                if (image.mime === 'image/svg+xml') {
                    return image.permalink;
                }
                
                for (const resource of image.resources) {
                    if (resource.width === 100 && resource.extension === 'webp') {
                        return resource.permalink;
                    }
                }
                
                return '';
            },
            fitWindow() {
                this.articleStyle.height = `${document.querySelector('footer').getBoundingClientRect().top - this.$refs.dropzone.getBoundingClientRect().top - 40}px`;
                this.fileListStyle.height = `${document.querySelector('footer').getBoundingClientRect().top - this.$refs.fileList.getBoundingClientRect().top - 60}px`;
            },
            sort() {
                let items = cloneObject(this.items);
                let folders = [];
                let files = [];
                
                for (let item of items) {
                    if (item.folder) {
                        folders.push(item);
                    } else {
                        files.push(item);
                    }
                }
                
                const sortedFolders = _.orderBy(folders, [folder => folder.name.toLowerCase()], ['asc']);
                const sortedFiles = _.orderBy(files, [file => file.name.toLowerCase()], ['asc']);
                
                this.items = sortedFolders.concat(sortedFiles);
            },
            makeFolder() {
                _axios.post(`files/make-folder`, {
                    folder: this.currentFolder,
                })
                      .then((response) => {
                          this.items.push(response.data);
                          this.sort();
                          this.editingNameID = findInArray(this.items, response.data.id);
                      });
            },
            delete() {
                if (!this.selectedItems.length) {
                    return;
                }
                
                let self = this, text;
                
                let selectedItemsBuffer = this.selectedItems;
                
                if (this.selectedItems.length === 1) {
                    let fileID = this.selectedItems[0];
                    let fileOffset = findInArray(this.items, fileID);
                    let file = this.items[fileOffset];
                    
                    if (file.folder === 1) {
                        text = `Deseja mesmo deletar a pasta <b>${file.name}</b>?`;
                    } else {
                        text = `Deseja mesmo deletar o arquivo <b>${file.name}</b>?`;
                    }
                } else {
                    text = `Deseja mesmo deletar esses <b>${this.selectedItems.length} itens</b>?`;
                }
                
                $.confirm({
                    title       : 'Atenção',
                    content     : text,
                    type        : 'red',
                    closeIcon   : true,
                    useBootstrap: true,
                    columnClass : 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3',
                    buttons     : {
                        confirmar: function () {
                            let items = cloneObject(self.items);
                            let folders = [];
                            let files = [];
                            
                            for (let item of items) {
                                if (selectedItemsBuffer.includes(item.id)) {
                                    continue;
                                }
                                
                                if (item.folder) {
                                    folders.push(item);
                                } else {
                                    files.push(item);
                                }
                            }
                            
                            const sortedFolders = _.orderBy(folders, [folder => folder.name.toLowerCase()], ['asc']);
                            const sortedFiles = _.orderBy(files, [file => file.name.toLowerCase()], ['asc']);
                            
                            self.items = sortedFolders.concat(sortedFiles);
                            
                            console.log(selectedItemsBuffer);
                            
                            _axios.delete(`files`, {
                                data: {
                                    items: selectedItemsBuffer,
                                }
                            });
                            
                            return true;
                        },
                        cancelar : function () {
                        }
                    }
                });
            },
            getListing() {
                this.$root.$emit('loadingState', true);
                this.items = [];
                this.selectedItems = [];
                
                this.$nextTick(function () {
                    _axios.get(`files/list/${this.currentFolder}`)
                          .then((response) => {
                              if (this.mimePattern) {
                                  for (let i = 0; i < response.data.length; i++) {
                                      if (response.data[i].folder || this.mimePattern.test(response.data[i].mime)) {
                                          this.items.push(response.data[i]);
                                      }
                                  }
                              } else {
                                  this.items = response.data;
                              }
                        
                              this.sort();
                              this.$root.$emit('loadingState', false);
                          });
                });
            },
            setName(id) {
                _axios.patch(`files/set-name/${this.items[id].id}`, {
                    name: this.items[id].name
                })
                      .then(this.sort());
            },
            truncate(str, maxsize) {
                if (str.length <= maxsize) {
                    return str;
                }
                
                return str.substr(0, maxsize) + '...';
            },
            clearSelection(e, context = false) {
                this.selectedItems = [];
                
                if (context) {
                    this.$nextTick(function () {
                        this.contextMenuOptions = this.contextMenuOptionsTypes.blank;
                        
                        this.$refs.contextMenu.open(e);
                    });
                }
            },
            itemClick(e, itemID, context = false) {
                if (context) {
                    this.$nextTick(function () {
                        let hasFile = this.selectionHasFile();
                        let hasFolder = this.selectionHasFolder();
                        
                        if (hasFile && hasFolder) {
                            this.contextMenuOptions = this.contextMenuOptionsTypes.both;
                        } else if (hasFile) {
                            let lastItemOffset = findInArray(this.items, this.lastClickedItem);
                            let lastItem = this.items[lastItemOffset];
                            
                            if (lastItem.mime.indexOf('image/') !== 0 && lastItem.mime.indexOf('application/pdf') !== 0) {
                                this.contextMenuOptionsTypes.file[1].disabled = true;
                                this.contextMenuOptionsTypes.file[2].default = !this.selector;
                            } else {
                                this.contextMenuOptionsTypes.file[1].disabled = false;
                                this.contextMenuOptionsTypes.file[1].default = !this.selector;
                                this.contextMenuOptionsTypes.file[2].default = false;
                            }
                            
                            this.contextMenuOptions = this.contextMenuOptionsTypes.file;
                        } else {
                            this.contextMenuOptions = this.contextMenuOptionsTypes.folder;
                        }
                        
                        this.$refs.contextMenu.open(e);
                    });
                } else {
                    this.$refs.contextMenu.close();
                }
                
                let oldClickedItem = this.lastClickedItem;
                this.lastClickedItem = itemID;
                
                if (this.holdingShift && this.holdingCtrl) {
                    return;
                }
                
                if (this.holdingCtrl) {
                    let pos;
                    
                    if ((pos = this.inSelection(itemID)) !== null && !context) {
                        this.selectedItems.splice(pos, 1);
                    } else {
                        if (!this.selectedItems.includes(itemID)) {
                            this.selectedItems.push(itemID);
                        }
                    }
                } else if (this.holdingShift) {
                    if (oldClickedItem === -1) {
                        this.selectedItems.push(itemID);
                        
                        return;
                    }
                    
                    if (oldClickedItem === itemID) {
                        return;
                    }
                    
                    let currentItemPos = findInArray(this.items, itemID);
                    let lastItemPos = findInArray(this.items, oldClickedItem);
                    let from = Math.min(currentItemPos, lastItemPos);
                    let to = Math.max(currentItemPos, lastItemPos);
                    
                    this.selectedItems = [];
                    
                    for (let i = from; i <= to; i++) {
                        if (this.inSelection(this.items[i].id) === null) {
                            this.selectedItems.push(this.items[i].id);
                        }
                    }
                } else {
                    if (context) {
                        if (this.inSelection(itemID) === null) {
                            this.selectedItems = [itemID];
                        }
                    } else {
                        this.selectedItems = [itemID];
                        
                        let currentTime = Date.now();
                        let lastItemPos = findInArray(this.items, oldClickedItem);
                        let lastItem = this.items[lastItemPos];
                        
                        if (!context && this.editingNameID === -1 && oldClickedItem === this.lastClickedItem && this.lastClickedTime + 400 >= currentTime) {
                            if (lastItem.folder === 1) {
                                this.openFolder(lastItem);
                            } else if (this.selection) {
                                this.contextMenuChose(null, 'select')
                            } else {
                                window.open(lastItem.permalink, '_blank').focus();
                            }
                            
                            return;
                        }
                        
                        this.lastClickedTime = currentTime;
                    }
                }
            },
            inSelection(id) {
                for (let i = 0; i < this.selectedItems.length; i++) {
                    if (this.selectedItems[i] === id) {
                        return i;
                    }
                }
                
                return null;
            },
            detectKeys(e) {
                this.holdingCtrl = !!e.ctrlKey;
                this.holdingShift = !!e.shiftKey;
            },
            detectShortcuts(e) {
                if (document.activeElement.nodeName === 'INPUT') {
                    return;
                }
                
                if (e.ctrlKey) {
                    if (e.keyCode === 67) {
                        this.contextMenuChose(null, 'copy');
                        e.preventDefault();
                    } else if (e.keyCode === 88) {
                        this.contextMenuChose(null, 'cut');
                        e.preventDefault();
                    } else if (e.keyCode === 86) {
                        this.contextMenuChose(null, 'paste');
                        e.preventDefault();
                    } else if (e.keyCode === 65) { // Ctrl + a
                        this.selectedItems = [];
                        
                        for (let i = 0; i < this.items.length; i++) {
                            this.selectedItems.push(this.items[i].id);
                        }
                        
                        e.preventDefault();
                    } else if (e.keyCode === 37) { //Ctrl + arrow left
                        if (this.lastClickedItem === -1) {
                            return;
                        }
                        
                        let pos = findInArray(this.items, this.lastClickedItem);
                        
                        if (--pos < 0) {
                            return;
                        }
                        
                        this.lastClickedItem = this.items[pos].id;
                        
                        e.preventDefault();
                    } else if (e.keyCode === 39) { //Ctrl + arrow right
                        if (this.lastClickedItem === -1) {
                            return;
                        }
                        
                        let pos = findInArray(this.items, this.lastClickedItem);
                        
                        if (++pos === this.items.length) {
                            return;
                        }
                        
                        this.lastClickedItem = this.items[pos].id;
                        
                        e.preventDefault();
                    } else if (e.keyCode === 38) { //Ctrl + arrow down
                        let itemAbove = this.getItemAbove(e);
                        
                        if (itemAbove !== null) {
                            this.lastClickedItem = parseInt(this.$refs.item[itemAbove].dataset.itemId);
                        }
                        
                        e.preventDefault();
                    } else if (e.keyCode === 40) { //Ctrl + arrow up
                        let itemBelow = this.getItemBelow(e);
                        
                        if (itemBelow !== null) {
                            this.lastClickedItem = parseInt(this.$refs.item[itemBelow].dataset.itemId);
                        }
                        
                        e.preventDefault();
                    } else if (e.keyCode === 32) {  //Ctrl + space
                        this.selectedItems.push(this.lastClickedItem);
                    }
                } else if (e.shiftKey) {
                    if (e.keyCode === 37) { // Shift + arrow left
                        if (this.lastClickedItem === -1) {
                            return;
                        }
                        
                        let pos = findInArray(this.items, this.lastClickedItem);
                        
                        if (--pos < 0) {
                            return;
                        }
                        
                        let itemOffset = this.inSelection(this.lastClickedItem);
                        
                        if (this.inSelection(this.items[pos].id) !== null) {
                            this.selectedItems.splice(itemOffset, 1);
                            this.lastClickedItem = this.items[pos].id;
                        } else {
                            if (!this.inSelection(this.lastClickedItem)) {
                                this.selectedItems.push(this.lastClickedItem);
                            }
                            
                            this.selectedItems.push(this.items[pos].id);
                            this.lastClickedItem = this.items[pos].id;
                        }
                        
                        e.preventDefault();
                    } else if (e.keyCode === 39) { // Shift + arrow right
                        if (this.lastClickedItem === -1) {
                            return;
                        }
                        
                        let pos = findInArray(this.items, this.lastClickedItem);
                        
                        if (++pos === this.items.length) {
                            return;
                        }
                        
                        let itemOffset = this.inSelection(this.lastClickedItem);
                        
                        if (this.inSelection(this.items[pos].id) !== null) {
                            this.selectedItems.splice(itemOffset, 1);
                            this.lastClickedItem = this.items[pos].id;
                        } else {
                            if (!this.inSelection(this.lastClickedItem)) {
                                this.selectedItems.push(this.lastClickedItem);
                            }
                            
                            this.selectedItems.push(this.items[pos].id);
                            this.lastClickedItem = this.items[pos].id;
                        }
                        
                        e.preventDefault();
                    } else if (e.keyCode === 38) { // Shift + arrow up
                        let itemAbove = this.getItemAbove();
                        
                        if (itemAbove === null) {
                            return;
                        }
                        
                        let start = findInArray(this.items, parseInt(this.$refs.item[itemAbove].dataset.itemId));
                        let end = findInArray(this.items, this.lastClickedItem);
                        let selectionItems = [], notSelectedItems = [];
                        
                        for (let i = start; i <= end; i++) {
                            selectionItems.push(this.items[i].id);
                            
                            if (this.inSelection(this.items[i].id) === null) {
                                notSelectedItems.push(this.items[i].id);
                            }
                        }
                        
                        if (!notSelectedItems.length) {
                            let selectedItemsBuffer = cloneObject(this.selectedItems);
                            this.selectedItems = [];
                            
                            for (let i = 0; i < selectedItemsBuffer.length; i++) {
                                if (!selectionItems.includes(selectedItemsBuffer[i])) {
                                    this.selectedItems.push(selectedItemsBuffer[i]);
                                }
                            }
                        } else {
                            for (let i = 0; i < selectionItems.length; i++) {
                                this.selectedItems.push(selectionItems[i]);
                            }
                        }
                        
                        if (this.inSelection(this.items[start].id) === null) {
                            this.selectedItems.push(this.items[start].id);
                        }
                        
                        this.lastClickedItem = this.items[start].id;
                    } else if (e.keyCode === 40) { // Shift + arrow down
                        let itemBelow = this.getItemBelow();
                        
                        if (itemBelow === null) {
                            return;
                        }
                        
                        let start = findInArray(this.items, this.lastClickedItem);
                        let end = findInArray(this.items, parseInt(this.$refs.item[itemBelow].dataset.itemId));
                        let selectionItems = [], notSelectedItems = [];
                        
                        for (let i = start; i <= end; i++) {
                            selectionItems.push(this.items[i].id);
                            
                            if (this.inSelection(this.items[i].id) === null) {
                                notSelectedItems.push(this.items[i].id);
                            }
                        }
                        
                        if (!notSelectedItems.length) {
                            let selectedItemsBuffer = cloneObject(this.selectedItems);
                            this.selectedItems = [];
                            
                            for (let i = 0; i < selectedItemsBuffer.length; i++) {
                                if (!selectionItems.includes(selectedItemsBuffer[i])) {
                                    this.selectedItems.push(selectedItemsBuffer[i]);
                                }
                            }
                        } else {
                            for (let i = 0; i < selectionItems.length; i++) {
                                this.selectedItems.push(selectionItems[i]);
                            }
                        }
                        
                        if (this.inSelection(this.items[end].id) === null) {
                            this.selectedItems.push(this.items[end].id);
                        }
                        
                        this.lastClickedItem = this.items[end].id;
                    } else if (e.keyCode === 32) { // Shift + space
                        if (this.lastClickedItem === -1) {
                            return;
                        }
                        
                        if (this.selectedItems.length === 0) {
                            this.selectedItems.push(this.lastClickedItem);
                            
                            return;
                        }
                        
                        let currentItemPos = findInArray(this.items, this.lastClickedItem);
                        let lastItemPos = findInArray(this.items, this.selectedItems[this.selectedItems.length - 1]);
                        let from = Math.min(currentItemPos, lastItemPos);
                        let to = Math.max(currentItemPos, lastItemPos);
                        
                        this.selectedItems = [];
                        
                        for (let i = from; i <= to; i++) {
                            if (this.inSelection(this.items[i].id) === null) {
                                this.selectedItems.push(this.items[i].id);
                            }
                        }
                    }
                } else if ((e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) { // Chars
                    if (this.lastChar !== e.key) {
                        this.charOffset = -1;
                    }
                    
                    this.lastChar = e.key;
                    this.charOffset++;
                    let offsetBuffer = this.charOffset;
                    let firstItem = null;
                    
                    for (let i = 0; i < this.items.length; i++) {
                        if (this.items[i].name.toLowerCase().indexOf(e.key) === 0) {
                            if (firstItem === null) {
                                firstItem = this.items[i].id;
                            }
                            
                            if (--offsetBuffer === -1) {
                                this.lastClickedItem = this.items[i].id;
                                this.selectedItems = [this.items[i].id];
                                
                                for (let k = 0; k < this.$refs.item.length; k++) {
                                    if (parseInt(this.$refs.item[k].dataset.itemId) === this.items[i].id) {
                                        this.$refs.item[k].scrollIntoView({block: "end", behavior: "smooth"});
                                        
                                        break;
                                    }
                                }
                                
                                return;
                            }
                        }
                    }
                    
                    if (firstItem !== null) {
                        this.lastClickedItem = firstItem;
                        this.selectedItems = [firstItem];
                        this.charOffset = 0;
                    }
                } else if (e.keyCode === 13) { // Enter
                    this.contextMenuChose(null, 'open');
                    e.preventDefault();
                } else if (e.keyCode === 8) { // Backspace
                    this.goBack();
                    e.preventDefault();
                } else if (e.keyCode === 46) { // Delete
                    this.contextMenuChose(null, 'delete');
                    e.preventDefault();
                } else if (e.keyCode === 37) { // Arrow left
                    if (this.lastClickedItem === -1) {
                        return;
                    }
                    
                    let itemOffset = findInArray(this.items, this.lastClickedItem);
                    
                    if (itemOffset - 1 >= 0) {
                        this.lastClickedItem = this.items[itemOffset - 1].id;
                        this.selectedItems = [this.items[itemOffset - 1].id];
                    }
                    
                    e.preventDefault();
                } else if (e.keyCode === 39) { // Arrow right
                    if (this.lastClickedItem === -1) {
                        return;
                    }
                    
                    let itemOffset = findInArray(this.items, this.lastClickedItem);
                    
                    if (itemOffset + 1 < this.items.length) {
                        this.lastClickedItem = this.items[itemOffset + 1].id;
                        this.selectedItems = [this.items[itemOffset + 1].id];
                    }
                    
                    e.preventDefault();
                } else if (e.keyCode === 38) { // Arrow up
                    let itemAbove = this.getItemAbove(e);
                    
                    if (itemAbove !== null) {
                        this.lastClickedItem = parseInt(this.$refs.item[itemAbove].dataset.itemId);
                        this.selectedItems = [this.lastClickedItem];
                    }
                    
                    e.preventDefault();
                } else if (e.keyCode === 40) { // Arrow down
                    let itemBelow = this.getItemBelow(e);
                    
                    if (itemBelow !== null) {
                        this.lastClickedItem = parseInt(this.$refs.item[itemBelow].dataset.itemId);
                        this.selectedItems = [this.lastClickedItem];
                    }
                    
                    e.preventDefault();
                } else if (e.keyCode === 32) { // Space
                    this.selectedItems.push(this.lastClickedItem);
                }
            },
            dragOver(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let dt = e.dataTransfer;
                
                if (dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') !== -1 : dt.types.contains('Files'))) {
                    this.showDropZone = true;
                    window.clearTimeout(this.dragTimer);
                }
            },
            dragLeave(e) {
                e.preventDefault();
                e.stopPropagation();
                
                this.dragTimer = window.setTimeout(() => {
                    this.showDropZone = false;
                }, 25);
            },
            drop(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!this.showDropZone) {
                    return;
                }
                
                this.showDropZone = false;
                
                this.formData = new FormData();
                this.formData.append('folder', this.currentFolder);
                
                let files = e.dataTransfer.files || e.target.files;
                
                for (let i = 0; i < files.length; i++) {
                    this.formData.append(`files[${i}]`, files[i]);
                }
            },
            contextMenuChose(optionOffset, option) {
                let slug;
                
                if (optionOffset !== null) {
                    slug = this.contextMenuOptions[optionOffset].slug;
                } else {
                    slug = option;
                }
                
                switch (slug) {
                    case 'select': {
                        let items = [], offset;
                        
                        for (let i = 0; i < this.selectedItems.length; i++) {
                            offset = findInArray(this.items, this.selectedItems[i]);
                            
                            if (!this.items[offset].folder && (this.mimePattern || this.mimePattern.test(this.items[offset].mime))) {
                                items.push(this.items[offset]);
                            }
                        }
                        
                        this.$emit('select', items);
                        
                        break;
                    }
                    case 'make-folder': {
                        this.makeFolder();
                        
                        break;
                    }
                    case 'open': {
                        let targetOffset = findInArray(this.items, this.lastClickedItem);
                        let target = this.items[targetOffset];
                        
                        if (target.folder) {
                            this.openFolder(target);
                        } else {
                            window.open(target.permalink, '_blank')
                                  .focus();
                        }
                        
                        break;
                    }
                    case 'download': {
                        let targetOffset = findInArray(this.items, this.lastClickedItem);
                        let target = this.items[targetOffset];
                        
                        window.open(target.permalink + '/1', '_blank')
                              .focus();
                        
                        break;
                    }
                    case 'copy-link': {
                        let targetOffset = findInArray(this.items, this.lastClickedItem);
                        let target = this.items[targetOffset];
                        this.copyToClipboard(target.permalink);
                        
                        break;
                    }
                    case 'cut':
                    case 'copy': {
                        if (!this.selectedItems.length) {
                            break;
                        }
                        
                        this.clipboard.mode = slug;
                        this.clipboard.items = this.selectedItems;
                        this.clipboard.itemsDetails = cloneObject(this.items);
                        
                        break;
                    }
                    case 'paste': {
                        if (!this.clipboard.items) {
                            break;
                        }
                        
                        let target;
                        
                        if (this.lastClickedItem !== -1) {
                            target = this.lastClickedItem;
                        } else {
                            target = this.currentFolder;
                        }
                        
                        let nestingError = false;
                        
                        for (let i = 0; (i < this.clipboard.items.length && !nestingError); i++) {
                            nestingError = !!this.currentPath.includes(this.clipboard.items[i]);
                        }
                        
                        if (nestingError) {
                            alert('Nesting error!');
                            
                            break;
                        }
                        
                        let itemOffset = findInArray(this.clipboard.itemsDetails, this.clipboard.items[0]);
                        let item = this.clipboard.itemsDetails[itemOffset];
                        
                        if (target === item.file_id) {
                            this.clipboard.mode = null;
                            this.clipboard.items = [];
                            
                            break;
                        }
                        
                        _axios.post(`files/${this.clipboard.mode}`, {
                            folder: this.currentFolder,
                            items : this.clipboard.items
                        })
                              .then(() => {
                                  this.getListing();
                              });
                        
                        this.clipboard.mode = null;
                        this.clipboard.items = [];
                        
                        break;
                    }
                    case 'rename': {
                        this.editingNameID = findInArray(this.items, this.lastClickedItem);
                        this.selectedItems = [this.editingNameID];
                        
                        break;
                    }
                    case 'meta': {
                        this.changeMeta();
                        
                        break;
                    }
                    case 'delete': {
                        this.delete();
                        
                        break;
                    }
                    case 'refresh': {
                        this.getListing();
                        
                        break;
                    }
                    case 'back': {
                        this.getListing();
                        
                        break;
                    }
                    case 'forward': {
                        this.getListing();
                        
                        break;
                    }
                }
            },
            openFolder(folder) {
                this.currentFolder = folder.id;
                this.currentPathName.push(folder.name);
                this.currentPath.push(folder.id);
                
                this.forwardPath = [];
                this.forwardPathName = [];
                
                this.getListing();
            },
            goRoot() {
                if (this.currentFolder === 1) {
                    return;
                }
                
                if (this.currentPath.length >= 1) {
                    this.selectedItems = [this.currentPath[1]];
                }
                
                this.forwardPath = [];
                this.forwardPathName = [];
                
                this.currentPathName = ['root'];
                this.currentPath = [1];
                
                this.currentFolder = 1;
                
                this.getListing();
            },
            goBack() {
                if (this.currentPath.length === 1) {
                    return;
                }
                
                let folder = this.currentPath.pop();
                
                this.selectedItems = [folder];
                
                this.forwardPath.push(folder);
                this.forwardPathName.push(this.currentPathName.pop());
                
                this.currentFolder = this.currentPath[this.currentPath.length - 1];
                
                this.getListing();
            },
            goForward() {
                if (this.forwardPath.length === 0) {
                    return;
                }
                
                this.currentFolder = this.forwardPath.pop();
                this.currentPathName.push(this.forwardPathName.pop());
                this.currentPath.push(this.currentFolder);
                
                this.getListing();
            },
            copyToClipboard(str) {
                const el = document.createElement('textarea');
                el.value = str;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.left = '-9999px';
                document.body.appendChild(el);
                const selected =
                    document.getSelection().rangeCount > 0
                        ? document.getSelection().getRangeAt(0)
                        : false;
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                
                if (selected) {
                    document.getSelection().removeAllRanges();
                    document.getSelection().addRange(selected);
                }
            },
            selectionHasFile() {
                let itemOffset;
                
                for (let i = 0; i < this.selectedItems.length; i++) {
                    itemOffset = findInArray(this.items, this.selectedItems[i]);
                    
                    if (!this.items[itemOffset].folder) {
                        return true;
                    }
                }
                
                return false;
            },
            selectionHasFolder() {
                let itemOffset;
                
                for (let i = 0; i < this.selectedItems.length; i++) {
                    itemOffset = findInArray(this.items, this.selectedItems[i]);
                    
                    if (this.items[itemOffset].folder) {
                        return true;
                    }
                }
                
                return false;
            },
            selectionImagesOnly() {
                let itemOffset;
                
                for (let i = 0; i < this.selectedItems.length; i++) {
                    itemOffset = findInArray(this.items, this.selectedItems[i]);
                    
                    if (this.items[itemOffset].folder || this.items[itemOffset].mime.indexOf('image/') !== 0) {
                        return false;
                    }
                }
                
                return true;
            },
            getExtension(file) {
                let filename = file.name;
                
                let results = filename.match(/\.[a-z0-9]+$/i);
                
                if (results && filename.length - results[0].length > 0) {
                    return results[0].toUpperCase().substr(1, results[0].length);
                }
                
                return 'FILE';
            },
            showUploadbox() {
                this.$refs.filesInput.click();
            },
            onFileChange(e) {
                this.formData = new FormData();
                this.formData.append('folder', this.currentFolder);
                
                let files = e.target.files || e.dataTransfer.files;
                
                for (let i = 0; i < files.length; i++) {
                    this.formData.append(`files[${i}]`, files[i]);
                }
            },
            parseSize(item) {
                if (item.folder) {
                    return '';
                }
                
                if (item.size === 0) {
                    return '0 KB'
                }
                
                
                let size = Math.max(Math.round(item.size / 1024), 1);
                let sizeStr = '';
                let sizeArr = size.toString().split('').reverse();
                
                for (let i = 0; i < sizeArr.length; i++) {
                    if (i && i % 3 === 0) {
                        sizeStr += '.';
                    }
                    
                    sizeStr += sizeArr[i];
                }
                
                return sizeStr.split('').reverse().join('') + ' KB';
            },
            parseDate(date) {
                let chunkDate = date.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2})\s((?:[0-9]+:?)+)$/);
                
                if (!chunkDate || chunkDate.length !== 5) {
                    return null;
                }
                
                return `${chunkDate[3]}/${chunkDate[2]}/${chunkDate[1]} ${chunkDate[4]}`;
            },
            getLastClieckedItemDomElementOffset() {
                if (this.lastClickedItem === -1) {
                    return;
                }
                
                let itemOffset;
                
                for (itemOffset = 0; itemOffset < this.$refs.item.length; itemOffset++) {
                    if (parseInt(this.$refs.item[itemOffset].dataset.itemId) === this.lastClickedItem) {
                        break;
                    }
                }
                
                return itemOffset;
            },
            getLastClieckedItemDomElement() {
                let offset = this.getLastClieckedItemDomElementOffset();
                
                if (offset === null) {
                    return null;
                }
                
                return this.$refs.item[offset];
            },
            getItemBelow() {
                let lestClickedItemDom = this.getLastClieckedItemDomElement(0);
                
                if (!lestClickedItemDom) {
                    return;
                }
                
                let currentItemPos = lestClickedItemDom.getBoundingClientRect();
                let currentItemDomOffset = this.getLastClieckedItemDomElementOffset();
                let itemPos, itemAbove = null;
                
                for (let i = currentItemDomOffset; i < this.$refs.item.length; i++) {
                    itemPos = this.$refs.item[i].getBoundingClientRect();
                    
                    if (itemPos.y === currentItemPos.y) {
                        continue;
                    }
                    
                    itemAbove = i;
                    
                    if (itemPos.x === currentItemPos.x) {
                        break;
                    }
                }
                
                return itemAbove;
            },
            getItemAbove() {
                let lestClickedItemDom = this.getLastClieckedItemDomElement(0);
                
                if (!lestClickedItemDom) {
                    return;
                }
                
                let currentItemPos = lestClickedItemDom.getBoundingClientRect();
                let currentItemDomOffset = this.getLastClieckedItemDomElementOffset();
                let itemPos, itemAbove = null;
                
                for (let i = currentItemDomOffset; i < this.$refs.item.length; i--) {
                    itemPos = this.$refs.item[i].getBoundingClientRect();
                    
                    if (itemPos.y === currentItemPos.y) {
                        continue;
                    }
                    
                    itemAbove = i;
                    
                    if (itemPos.x === currentItemPos.x) {
                        break;
                    }
                }
                
                
                return itemAbove;
            },
            changeMeta() {
                const file = this.items[findInArray(this.items, this.selectedItems[0])];
                
                $.confirm({
                    title       : file.name,
                    content     : `
                        <form class="form">
                            <div class="form-group">
                                <label for="title" class="col-form-label">
                                title:
                                </label>
                                <input type="text" id="__title" class="form-control" value="${file.title || ''}">
                            </div>
                            <div class="form-group">
                                <label for="__alt" class="col-form-label">
                                alt:
                                </label>
                                <input type="text" id="__alt" class="form-control" value="${file.alt || ''}">
                            </div>
                        </form>
                    `,
                    type        : 'red',
                    closeIcon   : true,
                    useBootstrap: true,
                    columnClass : 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3',
                    buttons     : {
                        confirmar: function () {
                            let title = document.getElementById('__title').value;
                            let alt = document.getElementById('__alt').value;
                            
                            file.title = title;
                            file.alt = alt;
                            
                            _axios.patch(`files/set-meta/${file.id}`, {
                                title: title,
                                alt  : alt
                            });
                            
                            return true;
                        },
                        cancelar : function () {
                        }
                    }
                });
            }
        }
    }
</script>

<style scoped>
    article {
        width: 99%;
        overflow: hidden;
    }
    
    .overflow-wrapper {
        display: flex;
        flex: 1;
        overflow: auto;
        width: 100%;
    }
    
    .overflow-content {
        display: flex;
        flex: 1;
        width: 100%;
    }
    
    .content-body {
        display: flex;
        flex: 1;
    }
    
    .files-wrapper {
        display: flex;
        position: relative;
        user-select: none;
        flex-wrap: wrap;
        
        align-items: flex-start;
        align-content: flex-start;
        
        padding-bottom: 20px;
    }
    
    .file-wrapper {
        width: 100px;
        height: 100px;
        
        display: inline-block;
    }
    
    .file {
        position: absolute;
        display: flex;
        flex-direction: column;
        
        width: 100px;
        max-height: 100px;
        
        padding: 6px;
        border: 1px solid transparent;
        border-radius: 2px;
        cursor: context-menu;
        
        /*transition: all .1s ease-out;*/
    }
    
    .file:hover {
        background-color: rgba(0, 61, 191, 0.15);
    }
    
    .file.selected {
        background-color: rgba(0, 61, 191, 0.3);
    }
    
    .file.last {
        max-height: 150px;
        border-color: #0074de;
        z-index: 10;
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
    
    .file.last .item-name {
        max-height: unset;
        
        white-space: unset;
        text-overflow: unset;
    }
    
    article >>> .ctx-menu {
        width: auto;
        min-width: unset;
        padding: 0;
    }
    
    .dropzone-overlay {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #00000088;
    }
    
    .dropzone-overlay .wrapper {
        width: calc(100% - 20px);
        height: calc(100% - 20px);
        display: flex;
        justify-content: center;
        align-items: center;
        border: 3px dashed #fff;
        color: #fff;
        font-size: 30px;
    }
    
    .buttons .button {
        position: relative;
        padding: 0 6px;
        float: left;
        color: #808080;
    }
    
    .buttons .button.enabled {
        color: #1b4b72;
    }
    
    .buttons .button.enabled:hover {
        color: #2770a8;
    }
    
    .navigation {
        display: flex;
        flex-direction: row;
        flex: 1;
        font-size: 25px;
    }
    
    .navigation .form-group {
        flex-grow: 1;
    }
    
    .img-fluid {
        align-self: center;
        flex: 0 0 auto;
        max-height: 100%;
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
    
    .cut-clipboard {
        opacity: 0.5;
    }
    
    .buttons .new-folder::after {
        content: '*';
        font-size: 34px;
        color: #b0d200;
        position: absolute;
        top: -8px;
        right: 1px;
        text-shadow: -1px 2px #00000088;
    }
    
    .buttons .copy::after {
        font-family: 'Font Awesome 5 Free';
        content: "\F07B";
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
        font-weight: 900;
        position: relative;
        left: -20px;
        top: -5px;
        text-shadow: -1px 2px #00000088;
        color: #b0d200;
        width: 5px;
    }
    
    .buttons .cut::after {
        content: '\F0C4';
        font-family: 'Font Awesome 5 Free';
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
        font-weight: 900;
        position: relative;
        left: 0;
        top: 0;
        font-size: 16px;
        text-shadow: 2px -1px #00000088;
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
        color: #b0d200;
        width: 4px;
    }
    
    .buttons .paste::after {
        content: '\F060';
        font-family: 'Font Awesome 5 Free';
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
        font-weight: 900;
        position: absolute;
        font-size: 12px;
        left: 20px;
        top: 17px;
        text-shadow: -1px 2px #00000088;
        color: #b0d200;
    }
    
    .buttons .upload::after {
        content: '\F062';
        font-family: 'Font Awesome 5 Free';
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
        font-weight: 900;
        position: absolute;
        font-size: 12px;
        left: 14px;
        top: 18px;
        text-shadow: -1px 2px #00000088;
        color: #b0d200;
    }
    
    .separator {
        position: relative;
        float: left;
        background-color: #b19191;
        width: 1px;
        height: 25px;
        margin: 7px 7px;
    }
    
    .buttons .button.delete.enabled {
        color: #8a0000;
    }
    
    .upload-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
    }
    
    .upload-progress .text {
        display: inline-block;
        background-color: #003ba2;
        color: #b3b3b3;
        padding: 2px 6px;
        border: 2px solid #8a8a8a;
        border-bottom: 0;
        border-radius: 2px;
    }
    
    .upload-progress .text.done {
        animation: progress-done 1.9s ease-in-out;
        animation-fill-mode: forwards;
    }
    
    @keyframes progress-done {
        0% {
            opacity: 1;
            background-color: #003ba2;
        }
        5% {
            opacity: 1;
            background-color: #00a23b;
        }
        90% {
            opacity: 1;
            background-color: #00a23b;
        }
        100% {
            opacity: 0;
            background-color: #00a23b;
        }
    }
    
    .item-icon[data-file] {
        color: #353535;
    }
    
    .files-wrapper.list-details .file-wrapper {
        height: 21px;
    }
    
    .files-wrapper.list-details .file-wrapper,
    .files-wrapper.list-details .file-wrapper .file {
        width: 60%;
    }
    
    .files-wrapper.list-details .file-wrapper .file {
        height: 22px;
        
    }
    
    .files-wrapper.list-details .file-wrapper:last-child {
        margin-bottom: 40px;
    }
    
    .files-wrapper.list-details .file-wrapper .file {
        flex-direction: row;
        padding: 2px 2px;
        border-radius: unset;
    }
    
    .files-wrapper.list-details .file-wrapper .file.last {
        max-height: unset;
    }
    
    .files-wrapper.list-details .file-wrapper .file .item-name {
        text-align: left;
    }
    
    .files-wrapper.list-details .file-wrapper .file .item-icon {
        margin-right: 4px;
    }
    
    .files-wrapper.list-details .file-wrapper .file input.item-name {
        padding: 2px 4px;
    }
    
    .files-wrapper.list-details .file-wrapper .file .item-icon[data-file="true"]::after {
        content: '';
    }
    
    .files-wrapper.list-details .file-wrapper .file .item-icon {
        font-size: 16px;
        height: 14px;
    }
    
    .files-wrapper.list-details .file-wrapper .file.editing {
        padding: 0 2px;
    }
    
    .files-wrapper.list-details .file-wrapper .file.editing .item-icon,
    .files-wrapper.list-details .file-wrapper .file.editing .date-created,
    .files-wrapper.list-details .file-wrapper .file.editing .size,
    .files-wrapper.list-details .file-wrapper .file.editing .mime {
        margin: 2px 0;
    }
    
    .files-wrapper .file .mime {
        width: 25%;
        font-size: 12px;
        line-height: 15px;
    }
    
    .files-wrapper .file .date-created {
        width: 19%;
        font-size: 12px;
        line-height: 15px;
    }
    
    .files-wrapper .file .size {
        width: 11%;
        font-size: 12px;
        line-height: 15px;
        text-align: right;
    }
    
    .list-details .item-name {
        width: 45%;
    }
    
    .file-list-heading {
        display: flex;
        width: 60%;
        font-size: 13px;
        padding: 2px 4px;
    }
    
    .file-list-heading .name {
        width: 45%;
    }
    
    .file-list-heading .date {
        width: 19%;
    }
    
    .file-list-heading .type {
        width: 25%;
    }
    
    .file-list-heading .size {
        width: 11%;
    }
    
    .file-list-heading > div {
        border-right: 1px solid #b9b9b9;
        padding: 0 10px;
    }
    
    .file-list-heading > div:first-child {
        padding: 0;
    }
</style>
