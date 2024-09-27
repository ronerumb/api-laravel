<template>
<div>
<table class="table table-hover">
                    <thead>
                        <tr>
                        <th scope="col" v-for="t in titulos" :key="t" class="text-capitalize">{{t}}</th>  
                        <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>  

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="obj in dados" :key="obj.id">    
                        <template v-for="valor,chave in obj" >                    
                        <td v-if="titulos.includes(chave)" :key="chave"
                          >
                            <span v-if="chave == 'imagem'">
                                <img :src="'/storage/'+valor" width="30px" height="30px">                      
                            </span>
                            <span v-else>
                                {{valor}}
                            </span>
                        </td>
                     
                        </template>
                           <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                            <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                            <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button>
                            <button v-if="remover" class="btn btn-outline-danger btn-sm">Remover</button>

                        </td>
                        </tr>
                       
                    </tbody>
                    </table>
    </div>
</template>

<script>
export default{
    props: [
        'dados','titulos','atualizar','visualizar','remover'
    ],
    methods:{
        setStore(obj){
            this.$store.state.item = obj
        }
    }
}
    
</script>
