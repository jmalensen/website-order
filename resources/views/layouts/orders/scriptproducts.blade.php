@push('js_after')
    <script type="text/javascript">
        $( document ).ready(function() {
            document.getElementById('order-form').addEventListener('keypress', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });
        });

        var vm = new Vue({
            el:'#myvueorder',
            data: {
                listProducts: $.parseJSON( '<?php echo addslashes(json_encode($products))?>'),
                searchWord: '',
                limitNB: 10, // Limit number of product saw on load
                listAddedProducts: {!! (!empty($productsAdded)) ? '$.parseJSON( \''.addslashes(json_encode($productsAdded)).'\')' : 'new Array()' !!},
                listUsers: {!! (!empty($users)) ? '$.parseJSON( \''.addslashes(json_encode($users)).'\')' : "''" !!},
                listCustomers: '',
                selectedUser: {!! (!empty($selectedUser)) ? $selectedUser : '0' !!},
                selectedCustomer: {!! (!empty($selectedCustomer)) ? $selectedCustomer : "''" !!},
            },
            created:function(){
                @if( !empty($users) )
                    this.changeCustomer( this.selectedUser );
                @endif

                if(this.listAddedProducts.length > 0){
                    let self = this;
                    $.each(this.listAddedProducts, function(key, value){
                        self.initAmount(key, this.product);
                    });
                }
            },
            computed:{
                productsFiltered() {
                    if(this.searchWord != ''){
                        return this.listProducts.filter((product) => {
                            this.showLess = false;
                            const value = this.searchWord.toLowerCase().split(' ');

                            if( product.description != null ){
                                return value.every(v => (product.code.toLowerCase().includes(v)
                                    || product.name.toLowerCase().includes(v)
                                    || product.description.toLowerCase().includes(v))
                                );
                            } else{
                                return value.every(v => (product.code.toLowerCase().includes(v)
                                    || product.name.toLowerCase().includes(v))
                                );
                            }
                        });
                    } else{
                        return this.limitNB ? this.listProducts.slice(0, this.limitNB) : this.listProducts;
                    }
                }
            },
            watch:{
                searchWord:function(){
                }
            },
            methods: {
                initAmount:function(index, product){

                    let result = this.checkAddedProduct(this.getIdProduct(index, product) );
                    let keyProduct = this.checkProduct(this.getIdProduct(index, product) );

                    this.listProducts[keyProduct].amount = this.listAddedProducts[ result['keyArr'] ].amount;
                },
                plusItem:function(index, product){

                    let result = this.checkAddedProduct(this.getIdProduct(index, product) );
                    let keyProduct = this.checkProduct(this.getIdProduct(index, product) );

                    // If can add, else update
                    if(result['canAddProduct']){
                        this.listProducts[keyProduct].amount += 1;
                        this.listAddedProducts.unshift( _.clone(product) );

                    } else if(result['keyArr'] != null){
                        this.listAddedProducts[ result['keyArr'] ].amount++;
                        this.listProducts[keyProduct].amount = this.listAddedProducts[ result['keyArr'] ].amount;
                    }
                },
                minusItem:function(index, product){

                    // If amount greater than 0
                    if(product.amount > 0){
                        let result = this.checkAddedProduct(this.getIdProduct(index, product) );
                        let keyProduct = this.checkProduct(this.getIdProduct(index, product) );

                        // Update
                        if(result['keyArr'] != null){
                            this.listAddedProducts[ result['keyArr'] ].amount--;
                            this.listProducts[keyProduct].amount = this.listAddedProducts[ result['keyArr'] ].amount;
                        }
                    }
                },
                saveAmount:function(index, product, event){

                    if(this.isInteger(event.target.value)){

                        let amount = parseInt(event.target.value);
                        let result = this.checkAddedProduct(this.getIdProduct(index, product) );
                        let keyProduct = this.checkProduct(this.getIdProduct(index, product) );

                        // If can add, else update
                        if(result['canAddProduct']){
                            this.listProducts[keyProduct].amount = amount;
                            this.listAddedProducts.unshift( _.clone(product) );

                        } else if(result['keyArr'] != null){
                            this.listAddedProducts[ result['keyArr'] ].amount = amount;
                            this.listProducts[keyProduct].amount = this.listAddedProducts[ result['keyArr'] ].amount;
                        }
                    }
                },
                saveAmountAdded:function(index, product, event){

                    if(this.isInteger(event.target.value)) {

                        let amount = parseInt(event.target.value);
                        let result = this.checkAddedProduct(product.id);
                        let keyProduct = this.checkProduct(product.id);

                        this.listProducts[keyProduct].amount = amount;
                        this.listAddedProducts[result['keyArr']].amount = amount;
                    }
                },
                checkProduct:function(productId){

                    let keyArr = null;

                    $.each(this.listProducts, function (key, oproduct) {

                        if(typeof oproduct.product !== 'undefined'
                            && oproduct.product.id !== ''
                            && productId === oproduct.product.id){

                            keyArr = key;
                        } else if(typeof oproduct.product === 'undefined'
                            && productId === oproduct.id){

                            keyArr = key;
                        }
                    });

                    return keyArr;
                },
                checkAddedProduct:function(productId){

                    let canAddProduct = true;
                    let keyArr = null;

                    $.each(this.listAddedProducts, function (key, oproduct) {

                        if(typeof oproduct.product !== 'undefined'
                            && oproduct.product.id !== ''
                            && productId === oproduct.product.id){

                            canAddProduct = false;
                            keyArr = key;
                        } else if(typeof oproduct.product === 'undefined'
                            && productId === oproduct.id){

                            canAddProduct = false;
                            keyArr = key;
                        }
                    });
                    let result = [];
                    result['canAddProduct'] = canAddProduct;
                    result['keyArr'] = keyArr;

                    return result;
                },
                deleteItem:function(index, product){

                    let keyProduct = this.checkProduct(this.getIdProduct(index, product) );

                    this.listAddedProducts[index].amount = 0;
                    this.listProducts[keyProduct].amount = 0;

                    // Remove product
                    this.listAddedProducts.splice(index, 1);
                },
                getCodeProduct:function(index, product){
                    if(typeof product.product !== 'undefined' && product.product.code !== ''){
                        return product.product.code;
                    } else{
                        return product.code;
                    }
                },
                getNameProduct:function(index, product){
                    if(typeof product.product !== 'undefined' && product.product.name !== ''){
                        return product.product.name;
                    } else{
                        return product.name;
                    }
                },
                getIdProduct:function(index, product){
                    if(typeof product.product !== 'undefined' && product.product.id !== ''){
                        return product.product.id;
                    } else{
                        return product.id;
                    }
                },
                isInteger:function(value){
                    return !isNaN(value)
                        && parseInt(Number(value)) == value
                        && !isNaN(parseInt(value, 10)) ;
                },
                changeCustomerEvent(event){
                    this.changeCustomer(event.target.value);
                },
                changeCustomer:function(value){
                    this.listCustomers = this.listUsers[value].customers;
                }
            }
        });
    </script>
@endpush