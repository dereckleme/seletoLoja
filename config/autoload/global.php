<?php
/**
 * Global Configuration Overridexxx
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'module_layouts' => array(
    		'Usuario' => 'layout/layout',
            'Base' => 'layout/site',
            'Produto' => 'Layout/adminProduto',
            'Pagamento'  => 'Layout/admin',
    ),
    'pagSeguroDereck' => array(
        'token' => 'EB9A55C4661F40F5A45B18043E4B4B52',
        'email' => 'pagseguro@grupomex.com.br',
        'currency' => 'BRL', #Indica a moeda na qual o pagamento será feito. No momento, a única opção disponível é BRL (Real). ‎terça-feira, ‎13‎ de ‎agosto‎ de ‎2013,
        'autenticado' => '1', # 1 - sim para gerar um token de compra é necessário esta logado. 2 - não precisa está logado.
        'SessionStorage' => "Usuario"   # Nome da sua Session Storage
    )
);
