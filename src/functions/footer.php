<section>
<div class="container">
    <div class="columns">
      <div class="column">
        <br><br><br>
      </div>
    </div>
  </div>
</section>
<footer class="footer">
  <div class="content has-text-centered">
    <p>
      <strong>moviedriod</strong> by <a href="#">Mark Graham</a>. This website was totally made up by
      <a href="#">ME</a>.
    </p>
  </div>
</footer>
</body>
</html>
<script>
  var coll = document.getElementsByClassName("collapsible");
  var i;

  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function () {
      this.classList.toggle("active");
      var mcontent = this.nextElementSibling;
      if (mcontent.style.display === "block") {
        mcontent.style.display = "none";
      } else {
        mcontent.style.display = "block";
      }
    });
  }
</script>