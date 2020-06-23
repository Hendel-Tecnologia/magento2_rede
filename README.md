# Importante

Também é possível fazer o download da [última release](https://github.com/DevelopersRede/magento2/releases/latest/download/magento.zip). Essa versão já contém as dependências, então basta descompactar o pacote e enviá-lo para o servidor da plataforma.

# Módulo Magento 2

Esse módulo é suportado pelas versões 2.2 e 2.3 e os requisitos são os mesmo das respectivas versões da plataforma Magento.


## Instalação via release

Caso prefira, é possível instalar o módulo através de sua release. Para isso, basta **ir à raiz da instalação da plataforma Magento** e seguir os seguintes passos:

1. Crie o diretório do módulo:
   * `mkdir -p app/code/Rede/Adquirencia`
2. Faça o download da última release do módulo:
   * `wget https://github.com/Hendel-Tecnologia/magento2_rede/archive/1.2.1-h.zip -O app/code/Rede/Adquirencia/magento.zip`
3. Descompacte o módulo:
   * `unzip app/code/Rede/Adquirencia/magento.zip -d app/code/Rede/Adquirencia/`
4. Apague o zip:
   * `rm app/code/Rede/Adquirencia/magento.zip`
5. Instale o SDK PHP:
   * `composer require developersrede/erede-php`
6. Atualize a instalação:
   * `php bin/magento setup:upgrade`
