<template>
  <div class="row">
    <div class="col-sm navbar-text">
      <a class="btn btn-sm btn-outline-secondary" v-on:click.prevent="alterShift">{{btnText}}</a>
      {{shiftText}} {{this.date}} -
      Time:
      <span>{{ hours}}h</span>:
      <span>{{ minutes | two_digits }}m</span>:
      <span>{{ seconds | two_digits }}s</span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      now: null,
      date: null,
      shiftText: "You have not done a shift yet",
      btnText: "Start Shift"
    };
  },
  methods: {
    alterShift() {
      axios
        .put("api/alter/shift/" + this.$store.state.user.id)
        .then(response => {
          let messageText = "You have ended your shift";
          this.$store.commit("setUser", response.data);
          this.updateShiftInfo();
          if (response.data.shift_active) {
            messageText = "You have started your shift";
          }
          this.flashMessage.info({
            title: "",
            message: messageText
          });
        })
        .catch(error => {
          this.errors = error.response.data.errors;
        });
        
    },
    updateShiftInfo() {
      if (this.$store.state.user.shift_active != null) {
        if (this.$store.state.user.shift_active) {
          this.date = this.$store.state.user.shift_start;
          this.shiftText = "Current shift strated at:";
          this.btnText = "End Shift";
        } else {
          this.date = this.$store.state.user.shift_end;
          this.shiftText = "Last shift ended at:";
          this.btnText = "Start Shift";
        }
      }
    }
  },
  computed: {
    seconds() {
      var seconds = (this.now - this.remainDateValue) % 60;
      if (seconds >= 0) {
        return (this.now - this.remainDateValue) % 60;
      } else {
        return 0;
      }
    },
    minutes() {
      var seconds = ((this.now - this.remainDateValue) / 60) % 60;
      if (seconds >= 0) {
        return Math.trunc((this.now - this.remainDateValue) / 60) % 60;
      } else {
        return 0;
      }
    },
    hours() {
      var seconds = (this.now - this.remainDateValue) / 3600;
      if (seconds >= 0) {
        return Math.trunc((this.now - this.remainDateValue) / 3600);
      } else {
        return 0;
      }
    },
    remainDateValue() {
      return Math.trunc(Date.parse(this.date) / 1000);
    }
  },
  created() {
    window.setInterval(() => {
      this.now = (new Date(Date.now()).getTime() / 1000) | 0;
    }, 1000);
    this.updateShiftInfo();
  },
  filters: {
    two_digits: function(value) {
      if (value.toString().length <= 1) {
        return "0" + value.toString();
      }
      return value.toString();
    }
  }
};
</script>

<style>
</style>
