<template>
    <div class="row">
        <div class="media">
            <img class="mr-3" :src= user.photo_url />
        </div>
        
         <div class="col-sm">
            <h4>Name: </h4>
            {{user.name}}
            <h4>User Name: </h4>
            {{user.username}}
            <h4>Email: </h4>
            {{user.email}} 
        </div>
    </div>
</template>

<script>
export default {
    props: ['user',]
}
</script>

<style>

</style>
