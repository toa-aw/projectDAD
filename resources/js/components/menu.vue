<template>

    <div>
      <div class="jumbotron"> 
          <h1>{{ title }}</h1>
      </div>
        <div class="row">
          <item-list :items="meals" title="Meals" ref="itemListRef" class="col-sm"/>
          <item-list :items="drinks" title="Drinks" ref="itemListRef" class="col-sm"/>
        </div>
    </div>
</template>

<script type="text/javascript">
import ItemList from "./itemList.vue";

export default {
  data: function() {
    return {
      title: "Menu",
      items: [],
      drinks: [],
      meals: []
    };
  },
  methods: {
    getItems: function() {
      if(this.$store.state.items.length == 0){
       axios
      .get('api/items')
      .then(response =>  {
        this.items = response.data.data;
        this.organizeMenu();
        this.$store.commit('setItems', this.items);
      });
      
      }else{
        this.items = this.$store.state.items;
        this.organizeMenu()
      }
    },

    organizeMenu: function () {
        this.items.forEach(item => {
          if(item.type === 'drink'){
            this.drinks.push(item);
          }else if(item.type === 'dish'){
            this.meals.push(item);
          };
        });
    }
  }, 
  components: {
    'item-list': ItemList,
  }, 
  mounted() {
    this.getItems();
  }
};
</script>

