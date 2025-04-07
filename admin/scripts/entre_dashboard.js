function booking_analytics() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/entre_dashboard.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
      if(this.status === 200) {
          let data = JSON.parse(this.responseText);
          document.getElementById('total_bookings').textContent = data.total_bookings;
          document.getElementById('pending_bookings').textContent = data.pending_bookings;
      }
  }

  xhr.send('booking_analytics');
}

function user_analytics() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/entre_dashboard.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
      if(this.status === 200) {
          let data = JSON.parse(this.responseText);
          document.getElementById('total_users').textContent = data.total_users;
          document.getElementById('total_reviews').textContent = data.total_reviews;
      }
  }

  xhr.send('user_analytics');
}

window.onload = function() {
  booking_analytics();
  user_analytics();
}