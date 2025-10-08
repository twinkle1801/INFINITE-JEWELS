<?php
// about.php
include 'header.php';
?>

<section class="about-section">

  <!-- Row 1: Image Left | Content Right -->
  <div class="about-row">
    <div class="about-img">
      <img src="admin/aboutus1.jpg" alt="Our Story - Infinite Jewels">
    </div>
    <div class="about-content">
      <h2>OUR STORY</h2>
<p>
  At <strong>Infinite Jewels</strong>, every creation is more than just jewelry 
  it is a celebration of elegance, emotion, and individuality. 
  What began as a vision to bring timeless artistry into everyday life 
  has grown into a brand that redefines luxury for the modern generation.
</p>
<p>
  Each jewel is thoughtfully designed, blending 
  <em>contemporary style</em> with the richness of tradition. 
  From minimal classics to statement pieces, 
  our collections are crafted to reflect your story, 
  your strength, and your timeless beauty.
</p>

    </div>
  </div>

  <!-- Row 2: Content Left | Image Right -->
  <div class="about-row">
    <div class="about-content">
      <h2>A LEGACY OF LOVE</h2>
<p>
  At <strong>Infinite Jewels</strong>, every piece is more than just an ornament 
  it is a bond that passes from one generation to the next. 
  Like a grandmother adorning her granddaughter, 
  our jewels carry stories of love, blessings, and timeless traditions.
</p>
<p>
  Crafted with passion and precision, each collection celebrates 
  not only beauty but also the emotions that make moments unforgettable. 
  With every design, we honor the past while embracing the elegance of today.
</p>

    </div>
    <div class="about-img">
      <img src="admin/aboutus2.jpg" alt="Meet the Founders - Infinite Jewels">
    </div>
  </div>

  <!-- Row 3: Image Left | Content Right -->
  <div class="about-row">
    <div class="about-img">
      <img src="admin/aboutus3.jpg" alt="Our Legacy - Infinite Jewels">
    </div>
    <div class="about-content">
      <h2>OUR LEGACY</h2>
<p>
  Since 2022, <strong>Infinite Jewels</strong> has been redefining the way the 
  world experiences demi-fine jewelry. From our flagship boutique in 
  Koregaon Park, Pune, every masterpiece reflects India s timeless artistry, 
  now cherished in more than 200 countries.
</p>
<p>
  Picture a lady in our boutique, her eyes lighting up as she tries on a 
  necklace that feels made just for her. In that moment, elegance meets 
  emotion, and jewelry becomes more than an ornament  it becomes a memory.  
  From minimal everyday grace to grand festive statements, 
  <strong>Infinite Jewels</strong> is here to celebrate every chapter of your story.
</p>

    </div>
  </div>

</section>

<style>
.about-content h2 {
  font-size: 32px;
  font-weight: 700;
  color: #3a2e2e;
  margin-bottom: 20px;
  text-transform: capitalize;
  letter-spacing: 1px;
}

.about-content p {
  font-size: 18px;
  line-height: 1.9;
  color: #5c4b4b;
}

/* About Section Background */
.about-section {
  background: #fefcf9; /* soft skin/light beige shade */
  font-family: 'Poppins', sans-serif;
  padding: 60px 20px;
}

/* Headings */
.about-content h2 {
  font-size: 30px;
  font-weight: 700;
  color: #3a2e2e; /* deep brown for luxury feel */
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Paragraph */
.about-content p {
  font-size: 17px;
  line-height: 1.8;
  color: #5c4b4b; /* softer brown */
  margin-bottom: 15px;
}

/* Highlight words */
.about-content strong {
  color: #b48856; /* Gold accent */
  font-weight: 700;
}

.about-content em {
  font-style: italic;
  color: #8c6f4d; /* soft golden brown */
}

/* About Section */
.about-section {
  width: 100%;
  max-width: 1200px;
  margin: 50px auto;
  padding: 0 20px;
}

.about-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 60px;
  gap: 30px;
}

.about-row .about-img {
  flex: 1;
}

.about-row .about-img img {
  width: 100%;
  height: auto;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.about-row .about-content {
  flex: 1;
}

.about-row .about-content h2 {
  font-size: 28px;
  margin-bottom: 15px;
  color: #333;
  font-weight: 700;
}

.about-row .about-content p {
  font-size: 16px;
  line-height: 1.6;
  margin-bottom: 10px;
  color: #555;
}

/* Responsive Design */
@media (max-width: 768px) {
  .about-row {
    flex-direction: column;
    text-align: center;
  }

  .about-row:nth-child(even) {
    flex-direction: column-reverse; /* Row 2 ka alignment mobile me bhi maintain hoga */
  }

  .about-row .about-content h2 {
    font-size: 24px;
  }

  .about-row .about-content p {
    font-size: 15px;
  }
}
</style>



<?php
include 'footer.php';
?>
