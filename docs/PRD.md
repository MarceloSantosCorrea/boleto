# Documento de Requisitos do Produto (PRD)

## Visão Geral

Este projeto é uma biblioteca PHP para geração de boletos bancários em PDF, projetada para simplificar a criação de boletos para diversos bancos brasileiros. A biblioteca oferece uma interface unificada para gerar boletos compatíveis com as especificações da FEBRABAN, suportando múltiplos bancos e tipos de carteiras.

## Objetivo do Produto

Desenvolver uma biblioteca robusta e flexível que permita aos desenvolvedores gerar boletos bancários de forma programática, seguindo os padrões brasileiros e garantindo compatibilidade com os principais bancos do país.

## Público-Alvo

- **Desenvolvedores PHP**: Profissionais que precisam integrar geração de boletos em sistemas web
- **Sistemas de e-commerce**: Plataformas que necessitam de pagamento via boleto
- **Sistemas de gestão**: ERP e CRM que emitem boletos para clientes
- **Instituições financeiras**: Empresas que processam pagamentos via boleto

## Escopo do Produto

### Funcionalidades Principais

#### 1. Geração de Boletos
- Criação de boletos em formato PDF
- Layout padrão FEBRABAN
- Suporte a personalização de campos
- Geração de código de barras e linha digitável

#### 2. Suporte a Múltiplos Bancos
- **Bradesco**: Carteira 25 e outras carteiras
- **Itaú**: Diversas carteiras disponíveis
- **Caixa Econômica Federal**: Carteiras SIGCB
- **Sicoob**: Cooperativas de crédito
- **Sicredi**: Sistema de crédito cooperativo

#### 3. Entidades Principais

##### Cedente (Beneficiário)
- Nome completo
- CPF/CNPJ
- Endereço completo
- Dados bancários (agência, conta, DV)
- Código do cedente

##### Sacado (Pagador)
- Nome completo
- CPF/CNPJ
- Endereço completo (logradouro, número, bairro, cidade, UF, CEP)
- Informações de contato

##### Boleto
- Nosso número
- Número do documento
- Data de vencimento
- Valor do boleto
- Data de documento e processamento
- Instruções personalizadas
- Demonstrativos

#### 4. Cálculos Automáticos
- Fator de vencimento
- Dígito verificador do código de barras
- Dígito verificador do nosso número
- Linha digitável
- Módulo 10 e Módulo 11

#### 5. Geração de PDF
- Layout padrão bancário
- Código de barras 2of5 ou Code128
- Informações de recibo e ficha de compensação
- Suporte a logomarcas dos bancos

### Funcionalidades Adicionais

#### Geração de Carnês
- Suporte a múltiplas parcelas
- Geração de carnês em PDF
- Cálculo de vencimentos sequenciais

#### Utilitários
- Formatação de números
- Manipulação de datas
- Cálculos de módulo
- Validação de dados

## Requisitos Técnicos

### Requisitos de Sistema
- PHP 8.2 ou superior
- Extensões necessárias: GD, mbstring
- Bibliotecas de PDF (FPDF, mPDF)
- Memória mínima: 64MB

### Dependências
- **setasign/fpdf**: Geração de documentos PDF
- **twig/twig**: Template engine (para layouts customizáveis)
- **picqer/php-barcode-generator**: Geração de códigos de barra
- **mpdf/mpdf**: PDF generation avançada
- **pestphp/pest**: Framework de testes

### Arquitetura

#### Estrutura de Diretórios
```
boleto/
├── src/
│   ├── Boleto.php              # Entidade principal
│   ├── Cedente.php             # Entidade do beneficiário
│   ├── Sacado.php              # Entidade do pagador
│   ├── Avalista.php            # Entidade do avalista
│   ├── Banco/                  # Implementações por banco
│   │   ├── Bradesco.php
│   │   ├── Itau.php
│   │   ├── Caixa.php
│   │   ├── Sicoob.php
│   │   └── Sicredi.php
│   ├── Util/                   # Classes utilitárias
│   │   ├── Data.php
│   │   ├── Modulo.php
│   │   ├── Numero.php
│   │   ├── Substr.php
│   │   └── UnidadeMedida.php
│   ├── GeradorBoleto.php       # Gerador de PDF
│   ├── Gerador.php             # Gerador base
│   ├── CodigoBarras.php        # Manipulação de códigos de barra
│   └── Carne/                  # Módulo de carnês
│       └── Parcela.php
├── examples/                   # Exemplos de uso
├── tests/                      # Testes unitários
└── docs/                       # Documentação
```

#### Design Patterns
- **Strategy Pattern**: Para implementações por banco
- **Factory Pattern**: Para criação de instâncias
- **Template Method**: Para geração de PDFs
- **Builder Pattern**: Para construção de boletos complexos

## Casos de Uso

### Caso de Uso 1: Geração de Boleto Simples
**Cenário**: Um e-commerce precisa gerar um boleto para um pedido
1. Sistema recebe dados do pedido e cliente
2. Cria instância do boleto com banco desejado
3. Preenche dados do cedente (loja) e sacado (cliente)
4. Define valor, data de vencimento e instruções
5. Gera PDF do boleto
6. Envia boleto para o cliente

### Caso de Uso 2: Geração de Carnê
**Cenário**: Uma loja de móveis precisa gerar carnê para pagamento parcelado
1. Sistema define número de parcelas e intervalo
2. Cria múltiplos boletos com datas de vencimento sequenciais
3. Gera PDF único com todos os boletos
4. Envia carnê para o cliente

### Caso de Uso 3: Integração com Sistema ERP
**Cenário**: Sistema de gestão precisa emitir boletos para diversos clientes
1. ERP exporta dados de faturas
2. Biblioteca processa múltiplos boletos em lote
3. Gera PDFs individualizados
4. Salva boletos para envio posterior

## Requisitos Não-Funcionais

### Performance
- Tempo máximo de geração: 2 segundos por boleto
- Suporte a geração em lote: 100+ boletos simultâneos
- Uso de memória otimizado

### Segurança
- Validação de todos os dados de entrada
- Sanitização de dados sensíveis
- Proteção contra injeção de código

### Confiabilidade
- Cobertura de testes: mínimo 90%
- Validação de cálculos bancários
- Tratamento robusto de erros

### Compatibilidade
- Suporte a PHP 8.2+
- Compatibilidade com FEBRABAN
- Formato PDF padrão A4

## Limitações Atuais

1. **Bancos Suportados**: Apenas 5 bancos implementados
2. **Carteiras**: Algumas carteiras específicas não implementadas
3. **Layouts**: Layout fixo, sem customização avançada
4. **Exportação**: Apenas PDF, sem suporte a outros formatos
5. **Validação**: Validação básica de dados

## Cronograma de Implementação

### Fase 1: Núcleo do Sistema (Concluído)
- [x] Entidades básicas (Boleto, Cedente, Sacado)
- [x] Implementação bancos principais (Bradesco, Itaú, Caixa)
- [x] Geração de PDF básica
- [x] Cálculos bancários

### Fase 2: Expansão de Bancos
- [ ] Implementação remaining banks
- [ ] Suporte a mais carteiras
- [ ] Validação avançada

### Fase 3: Melhorias de Usabilidade
- [ ] API mais intuitiva
- [ ] Documentação completa
- [ ] Exemplos avançados

### Fase 4: Funcionalidades Avançadas
- [ ] Layouts customizáveis
- [ ] Exportação múltiplos formatos
- [ ] Integração com gateways de pagamento

## Métricas de Sucesso

- **Adoção**: Número de projetos utilizando a biblioteca
- **Satisfação**: Feedback positivo dos usuários
- **Confiabilidade**: Taxa de erros em produção < 1%
- **Performance**: Tempo de geração dentro do esperado
- **Cobertura**: Testes com cobertura > 90%

## Conclusão

Esta biblioteca preenche uma lacuna importante no ecossistema PHP brasileiro, oferecendo uma solução robusta e flexível para geração de boletos bancários. Com arquitetura modular e design pattern adequado, a biblioteca está preparada para evolução e expansão futura.

A implementação atual já oferece suporte aos principais bancos brasileiros e segue os padrões FEBRABAN, garantindo compatibilidade com os sistemas bancários existentes.