<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <card-component titulo="Busca de marcas">
                <template v-slot:conteudo>
                    <div class="form-row">
                        <div class="col mb-3">
                            <input-container-component titulo="ID" id=inputId id-help="idHelp" texto-ajuda="Opcional. Informe o ID da marca" >
                             <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                            </input-container-component> 
                        </div>
                        <div class="col mb-3">                            
                            <input-container-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca" >
                             <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca" v-model="busca.nome">
                            </input-container-component>                         
                        </div>                
                    </div>
                </template>
                <template v-slot:rodape>
                     <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                </template>
            </card-component>
           

            <!--- Fim card de busca -->



                <card-component titulo="Relação de marcas">                
                    <template v-slot:conteudo>                
                    <table-component 
                    :dados="marcas.data"
                    :visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar'}"
                    :atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar'}"
                    :remover="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover'}"
                    :titulos="['id','nome','imagem']">
                    </table-component>
                    </template>  
                    <template v-slot:rodape>
                    <div class="row">
                        <div class="col-10">
                        <paginate-component>
                            <li v-for=" l, key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                <a class="page-link" v-html="l.label"></a>
                            </li>
                           
                        </paginate-component>
                    </div>
                    <div class="col">
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </div>
                    </div>
                    </template>            
            </card-component>
        </div>
    </div>
             <!--- Inicio modal add marca -->
           <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus =='adicionado'"></alert-component>
                 <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar salvar a Marca" v-if="transacaoStatus =='erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
            <div class="form-group">
                 <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da marca" >
                    <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca" v-model="nomeMarca">
                </input-container-component>  
            </div>
                  <div class="form-group">
                 <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG" >
                    <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                </input-container-component>  
            </div>
            </template>
            <template v-slot:rodape>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                 <button type="button"  class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
           </modal-component>
            <!--- Fim modal add marca -->

             <!--- Inicio modal visualização de marca -->
              <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
                <template v-slot:alertas></template>
                 <template v-slot:conteudo>
                    <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                    </input-container-component>
                     <input-container-component titulo="Marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                    </input-container-component>
                     <input-container-component titulo="Imagem">
                       <img :src="'/storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem" width="100px" height="100px">     
                    </input-container-component>
                 </template>
                  <template v-slot:rodape>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
               
            </template>
              </modal-component>
             <!--- Fim modal visualização de marca -->

                       <!--- Inicio modal exclusao de marca -->
              <modal-component id="modalMarcaRemover" titulo="Remover Marca">
                <template v-slot:alertas>

                    <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Sucesso" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                     <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro" v-if="$store.state.transacao.status == 'erro'"></alert-component>

                </template>
                 <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                    <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                    </input-container-component>
                     <input-container-component titulo="Marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                    </input-container-component>                    
                 </template>
                  <template v-slot:rodape>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
               
            </template>
              </modal-component>
             <!--- Fim modal exclusao de marca -->

               <!--- Inicio modal atualizar marca -->
           <modal-component id="modalMarcaAtualizar" titulo="Atualizar Marca">
            <template v-slot:alertas>
                  <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Sucesso" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                     <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro" v-if="$store.state.transacao.status == 'erro'"></alert-component>

             </template>
            <template v-slot:conteudo>
            <div class="form-group">
                 <input-container-component titulo="Nome da Marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Informe o nome da marca" >
                    <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da marca" v-model="$store.state.item.nome">
                </input-container-component>  
            </div>
                  <div class="form-group">
                 <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG" >
                    <input type="file" class="form-control-file" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                </input-container-component>  
            </div>
            </template>
            <template v-slot:rodape>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                 <button type="button"  class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
           </modal-component>
            <!--- Fim modal atualizar marca -->

</div>

</template>

<script>

import inputContainer from './inputContainer.vue'
    export default {
  components: { inputContainer },
    computed: {
            token() {

                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })

                token = token.split('=')[1]
                token = 'Bearer ' + token

                return token
            }
        },
    data(){
        return {
            urlBase:'http://localhost:8000/api/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: [],
            marcas: { data: []},
            busca: {id: '', nome: ''}
        }
    },
        methods: {

            atualizar(){
                let formData = new FormData();
                formData.append('_method','patch')
                formData.append('nome',this.$store.state.item.nome)
                if(this.arquivoImagem[0]){
                formData.append('imagem',this.arquivoImagem[0])
                }

                let url = this.urlBase + '/' + this.$store.state.item.id
            

                 let config = {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(url,formData,config)
                .then(response=>{
                    atualizarImagem.value = ''
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = 'Registro atualizado com sucesso'
                    this.carregarLista()
                })
                .catch(errors =>{
                this.$store.state.transacao.status = 'erro'
                this.$store.state.transacao.mensagem = 'Ocorreu um erro na atualizacao do registro'+errors
                this.$store.state.transacao.dados = errors.response.data.errors
                })

            },

            remover(){
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')
                if(!confirmacao){
                    return false;
                }
                let formData = new FormData();
                formData.append('_method','delete')

               let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                let url = this.urlBase + '/' + this.$store.state.item.id
                axios.post(url,formData,config)
                .then(response =>{
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = 'Registro Removido com sucesso'
                    this.carregarLista()
                })
                .catch(errors =>{
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = 'Ocorreu um erro na remocação do registro'+errors;
                    console.log('Erro')
                })
            },

            pesquisar(){
                let filtro = ''
                for (let chave in this.busca){
                    if(this.busca[chave]){
                        if(filtro != ''){
                            filtro +=";" 
                        }
                        filtro +=chave + ':like:' + this.busca[chave]
                    }

                }
                if(filtro != ''){
                this.urlPaginacao = 'page=1'
                this.urlFiltro = '&filtro='+filtro
                }
                this.carregarLista()
            },
            paginacao(l){
                if(l.url){
                this.urlPaginacao = l.url.split('?')[1]
                this.carregarLista()
                }
            },
            carregarLista(){
                 let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                 let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                axios.get(url,config)
                .then(response =>{
                    this.marcas = response.data
                    

                })
                .catch(errors =>{
                    console.log(errors)
                })
            },


            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                let formData = new FormData();
                formData.append('nome',this.nomeMarca)
                formData.append('imagem',this.arquivoImagem[0])
                
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                axios.post(this.urlBase,formData,config)
                .then(response=>{
                    this.transacaoStatus = 'adicionado'
                    this.transacaoDetalhes = response
                    
                })
                .catch(errors =>{
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = errors.response
                    //console.log(errors.response.data.message)
                })
            }
        },
            mounted(){
                this.carregarLista()
            }
        }
    



   
</script>
