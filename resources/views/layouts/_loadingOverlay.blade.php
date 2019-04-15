<div class="loadingOverlay">
  <svg version="1.1" id="L5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
       viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
      <circle fill="#fff" stroke="none" cx="6" cy="50" r="6">
        <animateTransform
                attributeName="transform"
                dur="1s"
                type="translate"
                values="0 15 ; 0 -15; 0 15"
                repeatCount="indefinite"
                begin="0.1"/>
      </circle>
    <circle fill="#fff" stroke="none" cx="30" cy="50" r="6">
      <animateTransform
              attributeName="transform"
              dur="1s"
              type="translate"
              values="0 10 ; 0 -10; 0 10"
              repeatCount="indefinite"
              begin="0.2"/>
    </circle>
    <circle fill="#fff" stroke="none" cx="54" cy="50" r="6">
      <animateTransform
              attributeName="transform"
              dur="1s"
              type="translate"
              values="0 5 ; 0 -5; 0 5"
              repeatCount="indefinite"
              begin="0.3"/>
    </circle>
    </svg>
</div>

<style>
  .loadingOverlay {
    position: fixed;
    z-index: 100;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, .5);
  }

  .loadingOverlay svg{
    width: 100px;
    height: 100px;
    display:inline-block;
  }
</style>