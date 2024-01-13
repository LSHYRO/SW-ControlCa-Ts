</script>

<style>
.inicio-de-sesion {
  background-color: #ffffff;
  display: flex;
  flex-direction: row;
  justify-content: center;
  width: 100%;
}

.inicio-de-sesion .overlap-group-wrapper {
  background-color: #ffffff;
  height: 768px;
  width: 1360px;
}

.inicio-de-sesion .overlap-group {
  background-image: url('https://aprende.gob.mx/wp-content/uploads/2021/09/telesecundaria-01-scaled.jpg');
  background-position: 50% 50%;
  background-size: cover;
  height: 768px;
  position: relative;
}

.inicio-de-sesion .image {
  height: 637px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 77px;
  width: 1360px;
}

.inicio-de-sesion .floting-labels {
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: var(--CARDS);
  height: 435px;
  left: 288px;
  position: absolute;
  top: 167px;
  width: 785px;
}

.inicio-de-sesion .frame {
  background-color: #3a57e8;
  border-radius: 4px;
  box-shadow: 0px 2px 4px #3a57e84c;
  height: 47px;
  left: 284px;
  position: absolute;
  top: 359px;
  width: 266px;
}

.inicio-de-sesion .text-wrapper {
  color: #ffffff;
  font-family: "Inter", Helvetica;
  font-size: 20px;
  font-weight: 400;
  left: 21px;
  letter-spacing: 0;
  line-height: 35px;
  position: absolute;
  text-align: center;
  top: 5px;
  width: 224px;
}

.inicio-de-sesion .frame-wrapper {
  align-items: center;
  border: 1px solid;
  border-color: #eeeeee;
  border-radius: 4px;
  display: flex;
  justify-content: center;
  left: 42px;
  padding: 16px;
  position: absolute;
  top: 188px;
  width: 701px;
}

.inicio-de-sesion .div {
  align-items: flex-start;
  display: inline-flex;
  flex: 0 0 auto;
  flex-direction: column;
  justify-content: center;
  position: relative;
}

.inicio-de-sesion .text-wrapper-2 {
  color: var(--x8a92a6-text-color-2);
  font-family: var(--small-texts-13px-regular-inter-font-family);
  font-size: var(--small-texts-13px-regular-inter-font-size);
  font-style: var(--small-texts-13px-regular-inter-font-style);
  font-weight: var(--small-texts-13px-regular-inter-font-weight);
  letter-spacing: var(--small-texts-13px-regular-inter-letter-spacing);
  line-height: var(--small-texts-13px-regular-inter-line-height);
  margin-top: -1px;
  position: relative;
  width: 625px;
}

.inicio-de-sesion .rectangle {
  background-color: #000000;
  height: 19px;
  position: relative;
  width: 1px;
}

.inicio-de-sesion .div-wrapper {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 16px;
  left: 42px;
  position: absolute;
  top: 272px;
  width: 701px;
}

.inicio-de-sesion .frame-2 {
  align-items: flex-start;
  background-color: #ffffff;
  border: 1px solid;
  border-color: #eeeeee;
  border-radius: 4px;
  display: flex;
  flex: 0 0 auto;
  gap: 10px;
  padding: 16px;
  position: relative;
  width: 701px;
}

.inicio-de-sesion .text-wrapper-3 {
  color: #8a92a6;
  font-family: "Inter", Helvetica;
  font-size: 16px;
  font-weight: 400;
  letter-spacing: 0;
  line-height: 28px;
  margin-top: -1px;
  position: relative;
  width: 625px;
}

.inicio-de-sesion .p {
  color: #8a92a6;
  font-family: var(--h6-16px-regular-inter-font-family);
  font-size: var(--h6-16px-regular-inter-font-size);
  font-style: var(--h6-16px-regular-inter-font-style);
  font-weight: var(--h6-16px-regular-inter-font-weight);
  left: 74px;
  letter-spacing: var(--h6-16px-regular-inter-letter-spacing);
  line-height: var(--h6-16px-regular-inter-line-height);
  position: absolute;
  top: 103px;
  width: 637px;
}

.inicio-de-sesion .bienvenido {
  color: #232d42;
  font-family: "Inter", Helvetica;
  font-size: 30px;
  font-weight: 500;
  height: 45px;
  left: 275px;
  letter-spacing: 0;
  line-height: 52.5px;
  position: absolute;
  text-align: center;
  top: 31px;
  white-space: nowrap;
  width: 234px;
}
</style>

<script>
export default {
  name: "InicioDeSesion",
};

<template>
  <div class="inicio-de-sesion">
    <div class="overlap-group-wrapper">
      <div class="overlap-group">
        <img class="image" alt="Image" src="/img/image-1.png" />
        <div class="floting-labels">
          <div class="frame">
            <div class="text-wrapper">Iniciar sesión</div>
          </div>
          <div class="frame-wrapper">
            <div class="div">
              <div class="text-wrapper-2">Usuario</div>
              <div class="rectangle" />
            </div>
          </div>
          <div class="div-wrapper">
            <div class="frame-2">
              <div class="text-wrapper-3">Contraseña</div>
            </div>
          </div>
          <p class="p">
            Bienvenido al sistema de control de calificaciones de la escuela telesecundaria con la clave 20DTV1474D,
            para acceder a la informacón es necesario que inicies sesión.
          </p>
          <div class="bienvenido">Iniciar sesion</div>
        </div>
      </div>
    </div>
  </div>
</template>