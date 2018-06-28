const mutations = {
  changeTimebar(state,obj){
    var str=obj.str||"系统首页"
    var bool1=obj.showback||false //显示back
    var bool2=obj.showfrash===false?false:true  //显示刷新
    state.theader.label=str
    state.theader.showback=bool1
    state.theader.showfrash=bool2
  }
}

export default mutations
