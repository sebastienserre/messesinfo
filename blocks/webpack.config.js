const path = require('path');
module.exports = {
    entry: './blocks/messesinfo/index.jsx',
    output : {
        path: path.resolve(__dirname, 'blocks/messesinfo'),
        filename: 'index.js',
    },
    module:{
        rules:[
            {
                test: /.jsx?s/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            }
        ]
    }
}